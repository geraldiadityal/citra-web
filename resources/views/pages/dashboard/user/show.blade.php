<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User &raquo; {{$item->name}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-5">User Details</h2>

            <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-10">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-auto w-full">
                        <tbody>
                            <tr>
                                <th class="border px-6 py-4 text-right">Photo User</th>
                                <td class="border px-6 py-4"><img style="max-width: 80px;" src="{{ url('storage/'.$item->profile_photo_path) }}" /></td>

                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Nama User</th>
                                <td class="border px-6 py-4">{{ $item->name}}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Email User</th>
                                <td class="border px-6 py-4">{{ $item->email }}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Company Name</th>
                                <td class="border px-6 py-4">{{ $item->email}}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Phone Number</th>
                                <td class="border px-6 py-4">{{ $item->phone_number}}</td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right">Roles</th>
                                <td class="border px-6 py-4">{{ $item->roles}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>