import React, { useState } from 'react';
import { Formik, Field, Form, ErrorMessage, FieldProps } from 'formik';
import * as Yup from 'yup';
import OtpForm from './OtpForm'; 

const validationSchema = Yup.object({
    phoneNumber: Yup.string()
        .matches(/^[0-9]+$/, 'Nomor HP harus angka !')
        .min(11, 'Minimal 11 digit !')
        .max(14, 'Minimal 14 digit !')
        .required('Wajib diisi !'),
});

interface FormValues {
    phoneNumber: string;
}

export default function CardLogin() {
    const [showOtpForm, setShowOtpForm] = useState<boolean>(false);

    const initialValues: FormValues = { phoneNumber: '' };

    const handleSubmit = (values: FormValues) => {
        console.log(values);
        setShowOtpForm(true);
    };

    if (showOtpForm) {
        return <OtpForm/>;
    }

    return (
        <Formik
            initialValues={initialValues}
            validationSchema={validationSchema}
            onSubmit={handleSubmit}
        >
            {({ touched, errors, values }) => (
                <Form className="relative bg-white/30 backdrop-blur-lg border border-white/50 p-6 rounded-lg shadow-lg w-[700px] h-[250px]">
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

                    <button
                        type="button"
                        onClick={() => console.log(values.phoneNumber)}
                        className="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                    >
                        Show Phone Number
                    </button>

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
