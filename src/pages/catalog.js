import React from "react";
import reactDom from "react-dom";
import App from "components/catalog"

const el = document.getElementById('button_catalog');
reactDom.render(<App {...el.dataset} />, el)
