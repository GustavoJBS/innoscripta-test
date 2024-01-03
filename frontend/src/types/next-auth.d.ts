import NextAuth from 'next-auth'

declare module 'next-auth' {
	interface User {
		id: Number
		email: string
		name: string,
		created_at: Date,
		updated_at: Date,
		token: string
	}
	interface Session {
		user: User,
	}
}