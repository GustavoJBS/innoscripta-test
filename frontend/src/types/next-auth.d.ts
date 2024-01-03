import NextAuth from 'next-auth'

declare module 'next-auth' {

	interface User {
		id: Number
		email: string
		name: string,
		created_at: Date,
		updated_at: Date,
		preference: {
			id: Number,
			user_id: Number,
			languages: Array,
			sources: Array,
			categories: Array,
			created_at: Date,
			updated_at: Date,
		}
		token: string
	}
	interface Session {
		user: User,
	}
}