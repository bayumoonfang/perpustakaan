import React from "react";
import reactDom from "react-dom";
import App from "components/guest"

const el = document.getElementById('button_guest');
reactDom.render(<App {...el.dataset} />, el)
