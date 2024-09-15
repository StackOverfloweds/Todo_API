import React from 'react';

interface Todo {
  id: number;
  title: string;
  completed: boolean;
}

interface ListTodoProps {
  todos: Todo[];
  onComplete: (id: number) => void;
  onDelete: (id: number) => void;
}

const ListTodo: React.FC<ListTodoProps> = ({ todos, onComplete, onDelete }) => {
  return (
    <div className="mt-4">
      <div className="bg-white/20 backdrop-blur-md border border-white/30 rounded-lg p-4">
        {todos.map((todo) => (
          <div
            key={todo.id}
            className="glassmorphism-card p-4 mb-2 rounded-lg bg-white shadow-lg flex justify-between items-center"
          >
            <div>
              <p className={todo.completed ? 'line-through text-gray-500' : ''}>
                {todo.title}
              </p>
            </div>
            <div className="flex gap-2">
              <button
                onClick={() => onComplete(todo.id)}
                className="text-green-500 hover:underline"
              >
                Complete
              </button>
              <button
                onClick={() => onDelete(todo.id)}
                className="text-red-500 hover:underline"
              >
                Delete
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ListTodo;
