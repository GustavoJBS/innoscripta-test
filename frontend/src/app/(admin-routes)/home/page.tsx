'use client'

import Article from "@/components/Article";
import Filter from "@/components/Filter";
import Preference, { CheckboxArray, PreferenceInterface } from "@/components/Preference";
import { Divider, Pagination, Spinner } from "@nextui-org/react";
import axios from "axios";
import { useSession } from "next-auth/react";
import { useEffect, useState } from "react";
import toast from "react-hot-toast";

export interface FilterInterface {
    search: string,
    language: string,
    source: string,
    category: string,
    date: string
}

export interface ArticleInterface {
    id: string
    title: string
    url: string
    image: string
    published_at: string
    language: string
    category: {
        id: string,
        name: string,
        title: string
    },
    source: {
        id: string,
        name: string
    }
}

export default function Home() {
    const { data: session } = useSession()
    const [articles, setArticles] = useState([])
    const [preference, setPreference] = useState<PreferenceInterface|null>(null)
    const [loaded, setLoaded] = useState(false);
    const [loading, setLoading] = useState(false);
    const [page, setPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);

    const [languages, setLanguages] = useState<CheckboxArray[]>([]);
    const [sources, setSources] = useState<CheckboxArray[]>([]);
    const [categories, setCategories] = useState<CheckboxArray[]>([]);
    const [filters, setFilters] = useState<FilterInterface>({
        search: '',
        language: '',
        source: '',
        category: '',
        date: ''
    });


    useEffect(() => {
        if (!loaded && session?.user) {
            setLoaded(true)

            getFilterOptions()

            getUserPreference()
        }
    }, [session]);

    useEffect(() => {
        getArticles(1)
    }, [filters]);

    function updateFilters(filter: FilterInterface) {
        setPage(1)

        setFilters(filter)
    }
    
    function updatePreference(preference: PreferenceInterface) {
        if (session?.user.token) {
            axios.put(`${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/preference`, preference, {
                "headers": {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${session?.user.token}`
                }
            }).then(() => {
                toast.success('Preferences updated.')
                setPage(1)
                getArticles(1)
            }).catch(() => {
                toast.error('Failed to update preference.')
            })
        }
    }

    function getFilterOptions() {
        if (session?.user.token) {
            axios.get(`${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/articles/filters`, {
                "headers": {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${session?.user.token}`
                }
            }).then((response) => {
                setLanguages(response.data.languages)
                setSources(response.data.sources)
                setCategories(response.data.categories)
            }).catch(() => {
                toast.error('Failed to fetch filters.')
            })
        }
    }

    async function getUserPreference() {
        if (session?.user.token) {
            axios.get(`${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/preference`, {
                "headers": {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${session?.user.token}`
                }
            }).then((response) => {
                setPreference(response.data.preference)

                setPage(1)
                
                getArticles(1);
            }).catch(() => {
                toast.error('Failed to fetch articles.')
            })
        }  
    }

    async function getArticles(page: number) {
        if (session?.user.token) {
            setLoading(true)
            axios.get(`${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/articles`, {
                "headers": {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${session?.user.token}`
                },
                params: {
                    page,
                    filter: filters,
                }
            }).then((response) => {
                setArticles(response.data.articles.data)
                setLastPage(response.data.articles.last_page)
            }).catch(() => {
                toast.error('Failed to fetch articles.')
            }).finally(() => setLoading(false))
        }
    }

    function hasFilters() {
        return Boolean(categories.length 
            || languages.length 
            || sources.length)
    }

    return session && (
        <div className="w-full min-h-screen items-center justify-center my-4">
            {
                hasFilters() && preference && (
                    <Preference 
                        preference={preference}
                        setPreference={setPreference}
                        languages={languages}
                        sources={sources}
                        categories={categories}
                        updatePreference={updatePreference}
                    />
                )
            }
    
            <Divider className="my-2" />

            <Filter 
                filters={filters} 
                updateFilters={updateFilters}
                languages={languages}
            />

            {
                loading
                    ? (
                        <div className="w-full flex justify-center">
                            <Spinner />
                        </div>
                    ) 
                    : (
                        <div className="flex flex-col">
                            <div className="w-full grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                                {
                                    articles.map((article: ArticleInterface) => (
                                        <Article
                                            key={article.id}
                                            article={article}
                                        />
                                    ))
                                }
                            </div>

                            {
                                articles.length === 0 && (
                                    <div className="text-xl font-semibold w-full text-center">
                                        No Articles Found
                                    </div>
                                )
                            }
                        </div>
                    )
            }

            <div className="flex justify-center my-6">
                <Pagination 
                    showShadow 
                    showControls 
                    total={lastPage} 
                    initialPage={1} 
                    onChange={(page) => {setPage(page); getArticles(page)}} 
                    page={page}
                />
            </div>

        </div>
    )
}
