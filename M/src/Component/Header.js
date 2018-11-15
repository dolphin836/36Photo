import React from "react"
import { BrowserRouter as Router, Route, Link } from "react-router-dom";
import Index from "../Page/Index"
import Mark from "../Page/Mark"

const Header = props => (
    <Router>
        <header>
            <nav>
                <ul>
                    <li>
                        <Link to="/">Home</Link>
                    </li>
                    <li>
                        <Link to="/mark/">Mark</Link>
                    </li>
                </ul>
            </nav>

            <Route path="/" exact component={Index} />
            <Route path="/mark/" component={Mark} />
        </header>
    </Router>
)

export default Header
