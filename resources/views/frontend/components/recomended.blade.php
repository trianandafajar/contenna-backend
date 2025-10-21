{{-- <section class="bg-white p-5 rounded-lg shadow-md border border-gray-200 h-auto">
    <h3 class="text-lg font-bold mb-4">You Might Like These</h3>
    <div class="max-w-2xl mx-auto p-4">
        <div class="border-b-2 border-red-600 mb-4"></div>
        <div class="space-y-4">
            @foreach ($recommendedBlogs as $recommended)
                <a href="{{ route('single.show', $recommended->slug) }}"
                    class="hover:text-red-600 transition flex items-start space-x-4">
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">

                            {{ Str::limit($recommended->title, 40) }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            {{ $recommended->user->name ?? 'Unknown' }} |
                            {{ $recommended->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @php
                        $thumbnail = $recommended->thumbnail
                            ? (Str::startsWith($recommended->thumbnail, 'http')
                                ? $recommended->thumbnail
                                : asset('storage/' . $recommended->thumbnail))
                            : asset('assets/media/icons/duotune/modul/File_Explorer_Icon.webp');
                    @endphp
                    <img src="{{ $thumbnail }}" alt="{{ $recommended->title }}"
                        class="w-24 h-16 object-cover rounded">
                </a>
                <hr class="border-gray-300" />
            @endforeach
        </div>
    </div>
</section> --}}
