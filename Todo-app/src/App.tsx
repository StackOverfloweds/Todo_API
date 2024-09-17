import { Routes, Route } from 'react-router-dom';
import LoginPages from './pages/LoginPages';
import SignUpPages from './pages/SignUpPages';
import TodoListPage from './pages/TodoListPages';

function App() {
  return (
    <Routes>
      <Route path="/" element={<LoginPages />} />
      <Route path="/signup" element={<SignUpPages />} />
      <Route path="/todo" element={<TodoListPage />} />
    </Routes>
  );
}

export default App;
