import NextAuthSessionProvider from '@/providers/sessionProvider'
import './globals.css'
import type { Metadata } from 'next'

export const metadata: Metadata = {
    title: 'News Hub',
    description: 'News Aggregator',
}

export default function RootLayout({
    children,
}: {
    children: React.ReactNode
}) {
    return (
        <html lang="pt-br">
            <body>
                <NextAuthSessionProvider>{children}</NextAuthSessionProvider>
            </body>
        </html>
    )
}