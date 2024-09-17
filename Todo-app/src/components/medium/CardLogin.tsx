import React, { useState } from 'react';
import { Formik, Field, Form, ErrorMessage, FieldProps } from 'formik';
import * as Yup from 'yup';
import OtpForm from './OtpForm'; 
import { Link } from 'react-router-dom';

//skema validasi menggunakan yup
const validationSchema = Yup.object({
    phoneNumber: Yup.string()
        .matches(/^[0-9]+$/, 'Nomor HP harus angka !')
        .min(11, 'Minimal 11 digit !')//minimal 11 digit nomor
        .max(14, 'Minimal 14 digit !')//maksimal 14 digit nomor
        .required('Wajib diisi !'),
});

interface FormValues {
    phoneNumber: string;//deklarasi variabel
}

export default function CardLogin() {
    const [showOtpForm, setShowOtpForm] = useState<boolean>(false);//use state untuk menampilkan otp form

    const initialValues: FormValues = { phoneNumber: '' };//deklarasi nilai awal variabel

    const handleSubmit = (values: FormValues) => {//ketika button ditekan maka akan berpindah ke form otp
        console.log(values);
        setShowOtpForm(true);
    };

    if (showOtpForm) {//kondisi jika showOtpForm true
        return <OtpForm/>;
    }

    return (
        <Formik
            initialValues={initialValues}
            validationSchema={validationSchema}
            onSubmit={handleSubmit}
        >
            {({ touched, errors, values }) => (
                <Form className="relative bg-white/30 backdrop-blur-lg border border-white/50 p-6 rounded-lg shadow-lg w-[700px] h-[250px] flex flex-col justify-center opacity-80">
                    <h2 className="text-xl font-bold mb-4">Login</h2>
                    <div>
                        <label htmlFor="phoneNumber" className="block text-gray-800 text-sm font-medium mb-2">
                            Your Phone Number:
                        </label>
                        <Field name="phoneNumber">
                            {({ field }: FieldProps) => (
                                <input
                                    type="text"
                                    id="phoneNumber"
                                    placeholder="Your phone number..."
                                    className={`w-full p-2 border rounded-lg bg-transparent text-gray-900 focus:outline-none focus:ring-2 ${touched.phoneNumber && errors.phoneNumber
                                        ? 'border-red-600 focus:ring-red-600'
                                        : 'border-gray-300 focus:ring-blue-500'
                                        }`}
                                    {...field}
                                />
                            )}
                        </Field>
                        <ErrorMessage name="phoneNumber" component="div" className="text-red-600 text-sm mt-1" />
                    </div>
                    <p>Dont have an account ? <Link to={"/signup"} ><span>Sign Up</span></Link>  </p>
                    <button
                        type="submit"
                        className="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                    >
                        Submit
                    </button>
                </Form>
            )}
        </Formik>
    );
}
