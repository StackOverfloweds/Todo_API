import React, { useState } from 'react';

interface Todo {
  id: number;
  title: string;
  completed: boolean;
}

interface ListTodoProps {//menampilkan todos 
  todos: Todo[];
  onComplete: (id: number) => void;
  onDelete: (id: number) => void;
  onEdit: (id: number, newTitle: string) => void;
}

const ListTodo: React.FC<ListTodoProps> = ({ todos, onComplete, onDelete, onEdit }) => {
  const [editingTodoId, setEditingTodoId] = useState<number | null>(null);
  const [editTitle, setEditTitle] = useState<string>('');

  const handleEditClick = (todo: Todo) => {//membuka edit todos
    setEditingTodoId(todo.id);
    setEditTitle(todo.title);
  };

  const handleEditSubmit = (id: number) => {//mengirimkan editan todos
    onEdit(id, editTitle);
    setEditingTodoId(null); 
  };

  return (
    <div className="mt-4 flex flex-col gap-4">
      <div className="bg-white/20 backdrop-blur-md border border-white/30 rounded-lg flex flex-col gap-4">
        {todos.map((todo) => (
          <div
            key={todo.id}
            className="glassmorphism-card p-6 rounded-lg bg-white shadow-lg p-4 rounded flex justify-between items-center "
          >
            <div>
              {editingTodoId === todo.id ? (
                <input
                  type="text"
                  value={editTitle}
                  onChange={(e) => setEditTitle(e.target.value)}
                  className="p-2 border rounded w-full"
                />
              ) : (
                <p className={todo.completed ? 'line-through' : ''}>{todo.title}</p>
              )}
            </div>
            <div className="flex gap-5">
              {editingTodoId === todo.id ? (
                <button
                  onClick={() => handleEditSubmit(todo.id)}
                  className="text-blue-500"
                >
                  Save
                </button>
              ) : (
                <button onClick={() => handleEditClick(todo)} className="text-yellow-500">
                  Edit
                </button>
              )}
              <button onClick={() => onComplete(todo.id)} className="text-green-500">
                Complete
              </button>
              <button onClick={() => onDelete(todo.id)} className="text-red-500">
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
