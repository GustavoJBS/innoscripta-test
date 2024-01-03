'use client'

import Article from "@/components/Article";
import axios from "axios";
import { useSession } from "next-auth/react";
import { useEffect, useState } from "react";
import toast from "react-hot-toast";

export default function Home() {
    const { data: session } = useSession()
    const [articles, setArticles] = useState([])
    const [loaded, setLoaded] = useState(false);
    const [page, setPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);

    useEffect(() => {
        if (!loaded && session?.user) {
            setLoaded(true)

            getArticles()
        }
    }, [session?.user]);

    useEffect(() => {
        getArticles()
    }, [page])

    async function getArticles() {
        if (session?.user.token) {
            axios.get(`${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/articles`, {
                "headers": {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${session?.user.token}`
                },
                params: {
                    page: page
                }
            }).then((response) => {
                setArticles(response.data.articles.data)
                setLastPage(response.data.articles.last_page)
            }).catch(() => {
                toast.error('Failed to fetch articles.')
            })
        }
    }

    return session && (
        <div className="w-full min-h-screen items-center justify-center my-4">
            <h1 className="text-2xl mb-8">Olá, {session?.user.name}. Bem vindo(a)!</h1>
            <div className="flex flex-col">
                <div className="w-full grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                    {
                        articles.map((article: any) => (
                            <Article
                                key={article.id}
                                title={article.title}
                                imageUrl={String(article.source.name).includes('Guardian') ? '/guardian-logo.png' : article.image}
                                navigateUrl={article.url}
                                source={article.source}
                            />
                        ))
                    }
                </div>
            </div>

            <div className="flex justify-center my-6">
                <button
                    onClick={() => setPage(page - 1)}
                    className="cursor-pointer hover:opacity-60 duration-300"
                    disabled={page === 1}
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-12 h-12">
                        <path strokeLinecap="round" strokeLinejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>

                <span className="font-bold text-lg mx-4 my-auto">
                    {page}
                </span>

                <button
                    onClick={() => setPage(page + 1)}
                    className="cursor-pointer hover:opacity-60 duration-300"
                    disabled={page === lastPage}
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-12 h-12">
                        <path strokeLinecap="round" strokeLinejoin="round" d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
            </div>
        </div>
    )
}
