import React from 'react'
import Layout from '../components/Layout';

class Index extends React.Component {
    static async getInitialProps () {
        return { stars: 1 }
    }

    render() {
        return (
            <Layout title="首页" keywords="首页" description="首页">
                <h1>Home {this.props.stars}</h1>
            </Layout> 
        )
    }
}

export default Index;


