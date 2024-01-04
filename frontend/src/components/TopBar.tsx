'use client'
import { Button, Navbar, NavbarBrand, NavbarContent, NavbarItem, User } from "@nextui-org/react";
import { signOut, useSession } from "next-auth/react";


const TopBar = () => {
    const { data: session } = useSession()

    async function logout() {
		await signOut({
			redirect: true
		})
	}

    return (
        <Navbar position="static">
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
                    <Button onClick={logout}>
                        Sign Out
                    </Button>
                </NavbarItem>
            </NavbarContent>
        </Navbar>
    )
};

export default TopBar;