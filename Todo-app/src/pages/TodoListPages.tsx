import React, { useState } from 'react';
import TodoForm from '../components/medium/TodoForm';
import ListTodo from '../components/medium/TodoList';


interface Todo {
  id: number;
  title: string;
  completed: boolean;
}

const TodoListPage: React.FC = () => {
  const [todos, setTodos] = useState<Todo[]>([]);

  const handleAddTodo = (newTodo: Todo) => {
    setTodos((prevTodos) => [...prevTodos, newTodo]);
  };

  const handleCompleteTodo = (id: number) => {
    setTodos((prevTodos) =>
      prevTodos.map((todo) =>
        todo.id === id ? { ...todo, completed: !todo.completed } : todo
      )
    );
  };

  const handleDeleteTodo = (id: number) => {
    setTodos((prevTodos) => prevTodos.filter((todo) => todo.id !== id));
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-4xl font-bold mb-8 text-center">Todo List</h1>
      <TodoForm onAdd={handleAddTodo} />
      <ListTodo
        todos={todos}
        onComplete={handleCompleteTodo}
        onDelete={handleDeleteTodo}
      />
    </div>
  );
};

export default TodoListPage;
