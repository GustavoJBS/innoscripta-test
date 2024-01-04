'use client'

import { EyeFilledIcon } from "@/components/EyeFilledIcon";
import { EyeSlashFilledIcon } from "@/components/EyeSlashFilledIcon";
import { Button, Input } from "@nextui-org/react";
import { signIn } from "next-auth/react";
import { useRouter } from "next/navigation";
import { SyntheticEvent, useState } from "react";
import toast from "react-hot-toast";

export default function Login() {
    const [name, setName] = useState<string>('')
    const [email, setEmail] = useState<string>('')
    const [password, setPassword] = useState<string>('')
    const [confirmedPassword, setConfirmedPassword] = useState<string>('')
    const [isVisible, setIsVisible] = useState(false)

    const router = useRouter()

    async function handleSubmit(event: SyntheticEvent) {
        event.preventDefault()

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

        if (password != confirmedPassword) {
            toast.error('Confirm Password need to be equal to Password.')

            return
        }

        fetch(`${process.env.NEXT_PUBLIC_CLIENTSIDE_BACKEND_URL}/register`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify({
                name,
                email,
                password,
                confirmedPassword
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
        <div className="flex flex-1 flex-col justify-center px-6 lg:px-8 my-auto w-full">
            <h2 className="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                Sign Up a new Account
            </h2>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form noValidate className="space-y-6" onSubmit={handleSubmit}>
                    <Input
                        type="text"
                        label="Name"
                        labelPlacement="inside"
                        className="w-full mt-2 gap-1"
                        onChange={(e) => setName(e.target.value)}
                    />

                    <Input
                        type="email"
                        label="Email Address"
                        labelPlacement="inside"
                        className="w-full mt-2 gap-1"
                        onChange={(e) => setEmail(e.target.value)}
                    />

                    <Input
                        label="Password"
                        labelPlacement="inside"
                        className="w-full mt-2"
                        endContent={
                            <button className="focus:outline-none" type="button" onClick={() => setIsVisible(!isVisible)}>
                            {isVisible ? (
                                <EyeSlashFilledIcon className="text-2xl text-default-400 pointer-events-none" />
                            ) : (
                                <EyeFilledIcon className="text-2xl text-default-400 pointer-events-none" />
                            )}
                            </button>
                        }
                        onChange={(e) => setPassword(e.target.value)}
                        type={isVisible ? "text" : "password"}
                    />

                    <Input
                        label="Confirm Password"
                        labelPlacement="inside"
                        className="w-full mt-2"
                        endContent={
                            <button className="focus:outline-none" type="button" onClick={() => setIsVisible(!isVisible)}>
                            {isVisible ? (
                                <EyeSlashFilledIcon className="text-2xl text-default-400 pointer-events-none" />
                            ) : (
                                <EyeFilledIcon className="text-2xl text-default-400 pointer-events-none" />
                            )}
                            </button>
                        }
                        onChange={(e) => setConfirmedPassword(e.target.value)}
                        type={isVisible ? "text" : "password"}
                    />

                    <div className="flex flex-col gap-4 sm:flex-row">
                        <Button color="primary" variant="bordered" onClick={() => router.replace('/')} className="w-full">
                            Sign In
                        </Button>

                        <Button color="primary" type="submit"className="w-full">
                            Sign Up
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    )
}