import React from 'react'
import Link from 'next/link'

const Nav = props => (
    <nav>
        <ul>
            <li>
                <Link prefetch href="/">
                    <a>Home</a>
                </Link>
            </li>
            <li>
                <Link prefetch href="/about">
                    <a>About</a>
                </Link>
            </li>
        </ul>
        <style jsx>
            {`
                a {
                    text-decoration: underline;
                    color: green;
                }

                a:hover {
                    color: red;
                }
            `}
        </style>
    </nav>
)

export default Nav
