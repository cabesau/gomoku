<x-layout>
    <x-menu></x-menu>

    <h1 class="text-xl text-center p-12">待機室</h1><br>

    <div class="flex flex-col">
        <div class="flex">
            <div class="rounded border bg-yellow-500 p-3 flex-1">ルームID:{{$room->id}}</div>
            <div class="rounded border bg-yellow-500 p-3 flex-1">ゲームモード</div>
        </div>
        <div class="rounded border bg-yellow-500 p-3">対戦相手:{{$room->user->name}}</div>
    </div>

</x-layout>