import React from "react";
import reactDom from "react-dom";
import App from "components/dashboard"

const el = document.getElementById('root');
reactDom.render(<App {...el.dataset} />, el)
