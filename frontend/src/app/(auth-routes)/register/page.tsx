'use client'

import { signIn } from "next-auth/react";
import { useRouter } from "next/navigation";
import { SyntheticEvent, useState } from "react";
import toast from "react-hot-toast";

export default function Login() {
    const [name, setName] = useState<string>('')
    const [email, setEmail] = useState<string>('')
    const [password, setPassword] = useState<string>('')

    const router = useRouter()

    async function handleSubmit(event: SyntheticEvent) {
        if (!name) {
            toast.error('Name is required.')
            return
        }

        if (!email) {
            toast.error('Email is required.')
            return
        }

        if (!password) {
            toast.error('Password is required.')
            return
        }

        fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                name,
                email,
                password
            })
        }).then(async () => {
            const result = await signIn('credentials', {
                email,
                password,
                redirect: false
            })

            if (result?.error) {
                toast.error('Error trying to sign In.');
                return
            }

            router.replace('/home')
        })
    }

    return (
        <div className="flex flex-col items-center justify-center w-full h-screen">
            <h1 className="text-3xl mb-6">Sign Up</h1>

            <form className="w-[400px] flex flex-col gap-6" onSubmit={handleSubmit}>
                <input
                    className="h-12 rounded-md p-2 bg-transparent border border-gray-300"
                    type="text"
                    name="name"
                    placeholder="Write your Name"
                    onChange={(e) => setName(e.target.value)}
                />

                <input
                    className="h-12 rounded-md p-2 bg-transparent border border-gray-300"
                    type="text"
                    name="email"
                    placeholder="Write your Email"
                    onChange={(e) => setEmail(e.target.value)}
                />

                <input
                    className="h-12 rounded-md p-2 bg-transparent border border-gray-300"
                    type="password"
                    name="password"
                    placeholder="Write your Password"
                    onChange={(e) => setPassword(e.target.value)}
                />

                <div className="flex flex-col gap-4 sm:flex-row">
                    <button
                        type="button"
                        className="w-full h-12 rounded-md border-2 border-gray-200 text-gray-200 font-bold hover:opacity-70 duration-300"
                        onClick={() => router.replace('/')}
                    >
                        Sign In
                    </button>

                    <button
                        type="submit"
                        className="w-full h-12 rounded-md bg-gray-300 text-gray-800 font-bold hover:bg-gray-400 duration-300"
                    >
                        Sign Up
                    </button>
                </div>
            </form>
        </div>
    )
}