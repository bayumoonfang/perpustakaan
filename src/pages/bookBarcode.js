import React from "react";
import reactDom from "react-dom";
import App from "components/bookBarcode"

document.querySelectorAll('.barcode_button')
  .forEach(el => {
    reactDom.render(<App {...el.dataset} />, el )
  });