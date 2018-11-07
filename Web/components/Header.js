import React from 'react'
import Head from 'next/head'
import { string } from 'prop-types'

const defaultTitle = ''
const defaultKeywords = ''
const defaultDescription = ''

const Header = props => (
    <Head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{props.title || defaultTitle}</title>
        <meta name="keywords" content={props.keywords || defaultKeywords} />
        <meta name="description" content={props.description || defaultDescription} />
        <link rel="icon" href="/static/favicon.ico" />
        <style jsx global>
            {`
                body {
                    font-family: -apple-system, BlinkMacSystemFont, Avenir Next, Avenir, Helvetica, sans-serif;
                }
            `}
        </style>
    </Head>
)

Header.propTypes = {
  title: string,
  keywords: string,
  description: string
}

export default Header
