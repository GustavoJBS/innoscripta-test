import { NextAuthOptions } from "next-auth";
import CredentialsProvider from "next-auth/providers/credentials"

export const nextAuthOptions: NextAuthOptions = {
	providers: [
		CredentialsProvider({
			name: 'credentials',
			credentials: {
				email: { label: 'email', type: 'text' },
				password: { label: 'password', type: 'password' }
			},
			async authorize(credentials, req) {
				const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/login`, {
					method: 'POST',
					headers: {
						'Content-type': 'application/json'
					},
					body: JSON.stringify({
						email: credentials?.email,
						password: credentials?.password
					})
				})

				const userToken = await response.json()

				if (userToken && response.ok) {
					userToken.user.token = userToken.token;

					return userToken.user;
				}

				return null
			},
		})
	],
	pages: {
		signIn: '/'
	},
	callbacks: {
		async jwt({ token, user }) {
			user && (token.user = user)

			return token
		},
		async session({ session, token }) {
			session.user = token.user as any

			return session
		}
	}
}