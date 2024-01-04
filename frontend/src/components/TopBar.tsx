'use client'
import { Button, Navbar, NavbarBrand, NavbarContent, NavbarItem, User } from "@nextui-org/react";
import { signOut, useSession } from "next-auth/react";
import { LogoutIcon } from "./LogoutIcon";


const TopBar = () => {
    const { data: session } = useSession()

    async function logout() {
		await signOut({
			redirect: true
		})
	}

    return (
        <Navbar position="sticky">
            <NavbarContent 
                className={session?.user.name ? 'w-fit' : 'w-full'} 
                justify={session?.user.name ? 'start' : 'center'}
            >
                <NavbarBrand className="flex justify-center">
                    <p className="font-bold text-inherit text-center text-2xl">NEWS HUB</p>
                </NavbarBrand>
            </NavbarContent>

            {
                session?.user.name &&
                <NavbarContent justify="center"  className="hidden sm:flex gap-4">
                    <NavbarItem>
                        <User   
                            name={session?.user.name}
                            description="News Reader"
                        />
                    </NavbarItem>
                </NavbarContent>
            }

            {
                session?.user.name &&
                <NavbarContent justify="end">
                    <NavbarItem>
                        <Button isIconOnly onClick={logout}>
                            <LogoutIcon />
                        </Button>
                    </NavbarItem>
                </NavbarContent>
            }
        </Navbar>
    )
};

export default TopBar;