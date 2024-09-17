import CardLogin from "../components/medium/CardLogin";

export default function LoginPages() {
  return (
    <div className="relative flex justify-center items-center h-screen overflow-hidden">
      {/* Background Elements */}
      <div className="absolute h-60 w-60 bg-gray-300 rounded-md transform rotate-12 opacity-60" style={{ top: '20%', left: '10%' }}></div>
      <div className="absolute h-48 w-48 bg-gray-300 rounded-md transform rotate-2 opacity-70" style={{ top: '75%', left: '80%' }}></div>
      <div className="absolute h-56 w-56 bg-gray-300 rounded-md transform rotate-6 opacity-80" style={{ top: '50%', left: '50%' }}></div>
      <div className="absolute h-32 w-32 bg-gray-300 rounded-md transform rotate-12 opacity-90" style={{ top: '70%', left: '20%' }}></div>
      <div className="absolute h-64 w-64 bg-gray-300 rounded-md transform -rotate-12 opacity-70" style={{ top: '10%', left: '60%' }}></div>

      {/* Centered CardLogin */}
      <CardLogin />
    </div>
  );
}
