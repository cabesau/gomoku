<x-layout>
    <x-menu></x-menu>

    <h1 class="text-xl text-center p-12">待機室</h1><br>

    <div class="flex flex-col">
        <div class="flex">
            <div class="rounded border bg-yellow-500 p-3 flex-1">ルームNo:{{$room->room_no}}</div>
            <div class="rounded border bg-yellow-500 p-3 flex-1">ゲームモード：
                @if ($room->exciting_flg == 0)
                    通常モード
                @else
                    エキサイティングモード
                @endif

            </div>
        </div>
        <div class="rounded border bg-yellow-500 p-3">
            @if ($room_maker)
                対戦相手が来るまでお待ちください
            @else
                対戦相手:{{$room->user->name}}
            @endif
        </div>
        <div class="rounded border bg-yellow-500 p-3">コメント:{{$room->comment}}</div>

        @if ($room_maker)
        <form action="battle" method="post">
        @csrf
            <input type="hidden" name="room" value=$room>
            <div class="flex justify-center">
                <button type="submit" class="mb-8 py-3 px-4 mt-10 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800"id="createRoomBtn">
                    ゲームを開始する
                </button> 
            </div>  
        </form>
        @endif
    </div>

</x-layout>