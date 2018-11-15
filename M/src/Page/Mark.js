import React from "react";

const fetchMarks = cb =>
    cb([
        { id : 1, name: "A", count: 1 },
        { id : 2, name: "B", count: 2 }
    ]);

class Mark extends React.Component {
    constructor() {
        super();

        this.state = { marks: [] };
    }

    componentDidMount() {
        fetchMarks(marks => this.setState({ marks: marks }));
    }

    render() {
        return (
            <ul>
                {this.state.marks.map(mark => (
                    <li key={mark.id}>{mark.name}—{mark.count}</li>
                ))}
            </ul>
        );
    }
}

export default Mark