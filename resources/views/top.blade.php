<x-layout title="TOP | エキサイティング五目並べ">
<div class="p-4">

    <h1 class="text-xl text-center p-12">エキサイティング五目並べ</h1><br>

    <div class="flex justify-center">
        <div class="flex flex-col">
            <div class="flex-col"></div>
            <form  action="/game" method="post">
                @csrf
                <div class="space-y-1">
                    <span class="block pb-12">
                        <input type="text" name="name1" placeholder="プレイヤー１">
                        <input type="text" name="name2" placeholder="プレイヤー2">
                    </span>
                    <span class="block  text-center">
                                        
                    <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                    対戦する
                    </button>

                    </span>
                </div>

            </form>
        </div>
    </div>
</div>

</x-layout>