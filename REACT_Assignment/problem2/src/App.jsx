import React, { useState } from "react";
import "./App.css";

// Step 1 & 2: Create array of color objects
const MyColors = [
  { number: 4, color: "#AA7578"},
  { number: 7, color: "#A06A50"},
  { number: 9, color: "#A88462"},
  { number: 1, color: "#67755B"},
  { number: 6, color: "#3E828D"},
  { number: 5, color: "#687889"},
  { number: 8, color: "#69647B"},
  { number: 2, color: "#686868"},
  { number: 3, color: "#7B7366"}
];

// Step 3: Component for one block
function Block({ bgcolor, children }) {
  const [showValue, setShowValue] = useState(false); // hover show (temporary)
  const [showPermanentlyValue, setShowPermanentlyValue] = useState(false); // click show (permanent)
  const handleClick = (index) => {
    setShowPermanentlyValue(true);
  };

  const handleMouseEnter = () => {
    setShowValue(true);
  };

  const handleMouseLeave = () => {
    setShowValue(false);
  };

  return (
    <div
      className="block"
      style={{ backgroundColor: bgcolor }}
      onClick={handleClick} 
      onMouseEnter={handleMouseEnter}
      onMouseLeave={handleMouseLeave}
    >
      {showValue || showPermanentlyValue ? children : ""}
    </div>
  );
}


// Step 4: Component to render grid of blocks
function ColorGrid({ colorArray }) {
  return (
    <div className="grid">
      {colorArray.map((item, index) => (
        <Block key={index} bgcolor={item.color}> 
          {item.number}
        </Block>
      ))}
    </div>
  );
}

// Step 7: Render main component
export default function App() {
  return (
    <div className="App">
      <h1>Yuqing's Color Grid</h1>
      <ColorGrid colorArray={MyColors} />
    </div>
  );
}
