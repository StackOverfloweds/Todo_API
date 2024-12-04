import React, { useState, FormEvent } from 'react';
import { FaTasks } from 'react-icons/fa';

interface Todo {
  id: number;
  title: string;
  completed: boolean;
}

interface TodoFormProps {
  onAdd: (newTodo: Todo) => void;
}

const TodoForm: React.FC<TodoFormProps> = ({ onAdd }) => {
  const [title, setTitle] = useState<string>(''); 
  const [error, setError] = useState<string | null>(null); 

  const handleSubmit = (event: FormEvent<HTMLFormElement>) => {//untuk menambahkan todo Fungsi Add
    event.preventDefault();
    if (!title.trim()) {
      setError('Todo title cannot be empty');
      return;
    }

    const newTodo: Todo = {
      id: Date.now(), 
      title,
      completed: false,
    };

    
    onAdd(newTodo);
    setTitle('');
    setError(null);
  };

  return (
    <div className="container mx-auto p-4">
      {/* <h1 className="text-3xl font-bold mb-6 text-center">Add Todo</h1> */}
      <form onSubmit={handleSubmit} className="flex items-center space-x-2">
        <div className="relative flex-1">
          <input
            value={title}
            onChange={(e) => setTitle(e.target.value)}
            name="title"
            placeholder="Enter todo title"
            type="text"
            className={`p-2 pl-10 border ${error ? 'border-red-500' : 'border-gray-300'} rounded w-full`}
          />
          <span className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
            <FaTasks />
          </span>
        </div>
        <button type="submit" className="bg-blue-500 text-white py-2 px-4 rounded">
          Add Todo
        </button>
      </form>
      {error && <p className="text-red-500 text-center mt-4">{error}</p>}
    </div>
  );
};

export default TodoForm;
