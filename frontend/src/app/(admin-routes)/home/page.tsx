'use client'

import axios from "axios";
import { useSession } from "next-auth/react";
import { useEffect, useState } from "react";
import toast from "react-hot-toast";

export default function Home() {
    const { data: session } = useSession()
    const [articles, setArticles] = useState([])
    const [loaded, setLoaded] = useState(false)

    useEffect(() => {
        if (!loaded && session?.user) {
            setLoaded(true)

            getArticles()
        }
    }, [session?.user]);

    async function getArticles() {
        axios({
            "url": `${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/articles`,
            "method": "GET",
            "headers": {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${session?.user.token}`
            }
        }).then((response) => {
            setArticles(response.data.articles.data)
        }).catch(() => {
            toast.error('Failed to fetch articles.')
        })
    }

    return session && (
        <div className="w-full h-screen items-center justify-center">
            <h1 className="text-2xl mb-8">Olá, {session?.user.name}. Bem vindo(a)!</h1>
            <div className="flex flex-col">
                {
                    articles.map((article: any) => (
                        <div className="w-1/2 mb-4" key={article.id}>
                            <h1 className="text-xl mb-2">{article.title}</h1>
                            <p>{article.url}</p>
                        </div>
                    ))
                }
            </div>
        </div>
    )
}
