import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'

function App() {

    function Heading(props) {
      return <props.type>{props.children}</props.type>;
    }
  

  return (
    <>
        <Heading type="h2">This is an h2</Heading>
        <Heading type="h3">This is an h3</Heading>
    </>
  )
}

export default App
