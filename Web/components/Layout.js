import React from 'react'
import Header from './Header'
import Footer from './Footer'
import Nav from './Nav'

const Layout = (props) => (
    <div>
        <Header
            title={props.title}
            keywords={props.keywords}
            description={props.description}
        />
        <Nav />
        {props.children}
        <Footer />
    </div>
)

export default Layout