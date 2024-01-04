import { getServerSession } from "next-auth";
import { ReactNode } from "react";
import { redirect } from "next/navigation";
import { nextAuthOptions } from "@/utils/authOptions";
import { Toaster } from "react-hot-toast";
import TopBar from "@/components/TopBar";

interface PrivateLayoutProps {
	children: ReactNode
}

export default async function PrivateLayout({ children }: PrivateLayoutProps) {
	const session = await getServerSession(nextAuthOptions)

	if (session) {
		redirect('/home')
	}

	return (
		<div className="flex justify-center items-center flex-col min-h-screen">
			<TopBar />
			{children}
			<Toaster />
		</div>
	)
}