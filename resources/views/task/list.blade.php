<!-- Contoh isi index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            List Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium w-10 text-gray-500 uppercase tracking-wider">
                                Waktu
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium w-10 text-gray-500 uppercase tracking-wider">
                                Deadline
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-full">
                                Keterangan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 table-fixed">
                                7/4/2025 10:00 AM
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 table-fixed">
                                10/4/2025 10:00 AM
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-500 table-fixed">
                                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fuga vitae hic atque aut
                                voluptatum veniam laborum? Quasi, ipsa! Expedita dicta voluptate aut voluptatibus nobis
                                voluptas libero illum, amet incidunt maiores!
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium table-fixed">
                                <a href="#"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</a>
                                <a href="" type="button"
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Hapus</a>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
