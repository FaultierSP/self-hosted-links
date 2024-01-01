import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ auth }) {
  
    //Vars, constants and functions

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="PAGE TITLE" />
            
            <div className="py-2">
              <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div className="p-6 text-gray-900 flex items-center justify-end">



                  </div>
                </div>
              </div>
            </div>
        </AuthenticatedLayout>
    );
}
