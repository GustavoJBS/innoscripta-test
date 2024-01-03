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
            }).catch(() => {
                toast.error('Failed to fetch articles.')
            })
        }
    }

    return session && (
        <div className="w-full h-screen items-center justify-center">
            <h1 className="text-2xl mb-8">Olá, {session?.user.name}. Bem vindo(a)!</h1>
            <div className="flex flex-col">
                <div className="w-full grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {
                        articles.map((article: any) => (
                            <Article
                                key={article.id}
                                title={article.title}
                                description={article.description}
                                imageUrl={article.image}
                                navigateUrl={article.url}
                                source={article.source}
                            />
                        ))
                    }
                </div>
            </div>

            <button
                onClick={() => setPage(page - 1)}
            >
                Decrease Page
            </button>
            <button
                onClick={() => setPage(page + 1)}
            >
                Add Page
            </button>
        </div>
    )
}
