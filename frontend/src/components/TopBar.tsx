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
            <NavbarBrand>
                <p className="font-bold text-inherit">NEWS HUB</p>
            </NavbarBrand>

            {
                session?.user.name &&
                <NavbarContent justify="center">
                    <NavbarItem>
                        <User   
                            name={session?.user.name}
                            description="News Reader"
                        />
                    </NavbarItem>
                </NavbarContent>
            }

            <NavbarContent justify="end">
                <NavbarItem>
                    <Button isIconOnly onClick={logout}>
                        <LogoutIcon />
                    </Button>
                </NavbarItem>
            </NavbarContent>
        </Navbar>
    )
};

export default TopBar;