import React, { useState } from 'react';
import { Formik, Field, Form, ErrorMessage, FieldProps } from 'formik';
import * as Yup from 'yup';
import OtpForm from './OtpForm';
import { Link } from 'react-router-dom';

const validationSchema = Yup.object({
    phoneNumber: Yup.string()
        .matches(/^[0-9]+$/, 'Nomor HP harus angka !')
        .min(11, 'Minimal 11 digit !')
        .max(14, 'Maksimal 14 digit !')
        .required('Wajib diisi !'),
    gender: Yup.string().required('Pilih jenis kelamin !'),
});

interface FormValues {
    phoneNumber: string;
    gender: string;
}

export default function CardSignUp() {
    const [showOtpForm, setShowOtpForm] = useState<boolean>(false);

    const initialValues: FormValues = { phoneNumber: '', gender: '' };

    const handleSubmit = (values: FormValues) => {
        console.log(values);
        setShowOtpForm(true);
    };

    if (showOtpForm) {
        return <OtpForm />;
    }

    return (
        <Formik
            initialValues={initialValues}
            validationSchema={validationSchema}
            onSubmit={handleSubmit}
        >
            {({ touched, errors, values }) => (
                <Form className="relative bg-white/30 backdrop-blur-lg border border-white/50 p-6 rounded-lg shadow-lg w-[700px] h-auto flex flex-col justify-center opacity-80">
                    <h2 className="text-xl font-bold mb-4 text-center">Sign Up</h2>

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

                    <div className="mt-4">
                        <label htmlFor="gender" className="block text-gray-800 text-sm font-medium mb-2">
                            Your Gender:
                        </label>
                        <Field name="gender" as="select" className="w-full p-2 border rounded-lg bg-transparent text-gray-900 focus:outline-none focus:ring-2">
                            <option value="">Select your gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </Field>
                        <ErrorMessage name="gender" component="div" className="text-red-600 text-sm mt-1" />
                    </div>

                    <p className="mt-4 text-center">
                        Have an account? <Link to={"/"} className="text-blue-500 hover:underline">Sign In</Link>
                    </p>

                    <button
                        type="submit"
                        className="mt-4 w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                    >
                        Submit
                    </button>
                </Form>
            )}
        </Formik>
    );
}
