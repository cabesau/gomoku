<x-layout title="TOP | エキサイティング五目並べ">
<div class="p-4">

    <h1 class="text-xl text-center p-12">エキサイティング五目並べ</h1><br>

    <div class="flex flex-col">
        <div class="flex justify-center">
            <button type="submit" class="mb-8 py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                ルームを作成
            </button>
        </div>

    <table class="w-full">
        <thead>
            <tr>
                <th class="w-1/4">ルームID</th>
                <th class="w-1/4">ルーム作成者</th>
                <th class="w-1/4">コメント</th>
                <th class="w-1/4"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $room)
            <form action="/wait" method="POST">
            @csrf
            <tr>
                <td class="border border-gray-300 p-2">{{$room->id}}</td>
                <td class="border border-gray-300 p-2">{{$room->user->name}}</td>
                <td class="border border-gray-300 p-2">{{$room->comment}}</td>
                <td class="border border-gray-300 p-2"><button type="submit" class="w-full">入室する</button></td>
                <input type="hidden" value="{{$room->id}}" name="room_id">
            </tr>
            </form>
            @endforeach
        </tbody>
    </table>

</div>

</x-layout>