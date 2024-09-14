import React from 'react';
import { Formik, Field, Form, ErrorMessage, FieldProps } from 'formik';
import * as Yup from 'yup';

const otpValidationSchema = Yup.object({
    otp: Yup.string()
        .matches(/^[0-9]+$/, 'OTP harus angka !')
        .length(6, 'OTP harus 6 digit !')
        .required('Wajib diisi !'),
});

interface OtpFormValues {
    otp: string;
}

const OtpForm: React.FC = () => {
    const initialValues: OtpFormValues = { otp: '' };

    const handleSubmit = (values: OtpFormValues) => {
        console.log('OTP:', values.otp);
    };

    return (
        <Formik
            initialValues={initialValues}
            validationSchema={otpValidationSchema}
            onSubmit={handleSubmit}
        >
            {({ touched, errors }) => (
                <Form className="relative bg-white/30 backdrop-blur-lg border border-white/50 p-6 rounded-lg shadow-lg w-[700px] h-[250px]">
                    <h2 className="text-xl font-bold mb-4">Enter OTP</h2>
                    <div>
                        <label htmlFor="otp" className="block text-gray-800 text-sm font-medium mb-2">
                            OTP:
                        </label>
                        <Field name="otp">
                            {({ field }: FieldProps) => (
                                <input
                                    type="text"
                                    id="otp"
                                    placeholder="Enter OTP..."
                                    className={`w-full p-2 border rounded-lg bg-transparent text-gray-900 focus:outline-none focus:ring-2 ${touched.otp && errors.otp
                                        ? 'border-red-600 focus:ring-red-600'
                                        : 'border-gray-300 focus:ring-blue-500'
                                        }`}
                                    {...field}
                                />
                            )}
                        </Field>
                        <ErrorMessage name="otp" component="div" className="text-red-600 text-sm mt-1" />
                    </div>

                    <button
                        type="submit"
                        className="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                    >
                        Verify OTP
                    </button>
                </Form>
            )}
        </Formik>
    );
};

export default OtpForm;
