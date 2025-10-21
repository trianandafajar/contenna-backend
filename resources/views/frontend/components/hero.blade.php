<section class="position-relative py-5 bg-black text-center d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <img alt="Hero Background with Technology Theme" class="position-absolute top-0 start-0 w-100 h-100 object-cover opacity-50" 
    height="1080" src="https://wallpaperaccess.com/full/136499.png" width="1920" />

    <div class="container position-relative z-1">
        <h1 class="mb-5 display-5 fw-bold text-white">
            WELCOME TO <span class="text-docuverse">DOCUVERSE</span>
        </h1>

        <p class="mb-5 text-light mx-auto fs-3" style="max-width: 400px;">
            Search and find your documentation, Complete your ignorance into knowledge
        </p>

        <form action="{{ route('blogs') }}" method="get" class="d-flex justify-content-center mt-3">
            <div class="input-group w-75 w-md-50">
                <input type="text" class="form-control p-3" placeholder="Search by title, category, or tag..." name="q" 
                    value="{{ request('q') }}">
                <button class="btn btn-primary px-4 fw-semibold" type="submit">
                    Search
                </button>
            </div>
        </form>
        
    </div>
</section>
