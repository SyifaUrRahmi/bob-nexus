<style>
.memory-board{
display:grid;
grid-template-columns:
25px repeat(6,55px);
gap:15px;
}
.header{
display:flex;
justify-content:center;
align-items:center;
font-weight:bold;
font-size:14px;
}
.cell{

width:65px;

height:65px;

}

.drop-zone{

width:65px;

height:65px;

border:2px dashed #bfbfbf;

border-radius:8px;

display:flex;

justify-content:center;

align-items:center;

background:#fafafa;

transition:.2s;

}

.drop-zone:hover{

background:#eef6ff;

border-color:#0d6efd;

}

.image-pool{

display:grid;

grid-template-columns:repeat(6,50px);

gap:6px;

padding:8px;

background:#fff;

border:1px solid #ddd;

border-radius:10px;

width:fit-content;

margin:auto;
min-height: 290px; 
align-content: start;
}

.memory-item{

width:50px;

height:50px;

cursor:grab;

}

.memory-item img{

width:100%;

height:100%;

object-fit:cover;

border-radius:8px;

pointer-events:none;

box-shadow:0 2px 5px rgba(0,0,0,.15);


}

</style>
<form action="{{ route('answer.store') }}" method="POST" id="memoryForm">
    @csrf

    <input type="hidden" name="participant_id" value="{{ $participant->id }}">
    <input type="hidden" name="round_id" value="{{ $round->id }}">


    <div class="container-fluid">

        <div class="row justify-content-center align-items-start">

            {{-- GRID --}}
            <div class="col-lg-8 d-flex justify-content-center">

                <div class="memory-board">

                    {{-- Header --}}
                    <div></div>

                    @foreach(['A','B','C','D','E','F'] as $col)
                        <div class="header">{{ $col }}</div>
                    @endforeach

                    {{-- Grid --}}
                    @for($row=1;$row<=5;$row++)

                        <div class="header">
                            {{ $row }}
                        </div>

                        @foreach(['A','B','C','D','E','F'] as $col)

                            <div class="cell">

                                <div
                                    class="drop-zone"
                                    id="zone-{{ $row }}-{{ $col }}"
                                    data-row="{{ $row }}"
                                    data-col="{{ $col }}">
                                </div>
                                <input
                                    type="hidden"
                                    id="answer-{{ $row }}-{{ $col }}"
                                    name="answer[{{ $row }}][{{ $col }}]">

                            </div>

                        @endforeach

                    @endfor

                </div>

            </div>


            {{-- PANEL GAMBAR --}}
            <div class="col-lg-4 d-flex flex-column">

                <h5 class="mb-3">
                    Gambar Acak
                </h5>

                <div id="imagePool" class="image-pool">

                    @foreach($images as $image)

                        <div class="memory-item">

                            <img
                                src="{{ asset('storage/'.$image) }}"
                                data-image="{{ $image }}"
                                draggable="false">

                        </div>

                    @endforeach

                </div>
                <div class="mt-2">
            </div>
            <div>
                <button
                    type="submit"
                        class="btn btn-success submit-btn mt-5" style>SUBMIT
                </button>
            </div>

            </div>
            

        </div>
        


    </div>
    
                        

</form>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

// Pool gambar
new Sortable(document.getElementById('imagePool'), {
    group: 'memory',
    animation: 200,
    sort: true,

    onAdd: function () {
        updateAnswers();
    },

    onRemove: function () {
        updateAnswers();
    }
});


// Semua kotak jawaban
document.querySelectorAll('.drop-zone').forEach(zone => {

    new Sortable(zone, {

        group: 'memory',

        animation: 200,

        swapThreshold: 0.7,

        onAdd: function (evt) {

            // Hanya boleh satu gambar
            if (evt.to.children.length > 1) {

                // Kembalikan gambar sebelumnya ke pool
                evt.from.appendChild(evt.to.children[0]);
            }

            updateAnswers();
        },

        onRemove: function () {
            updateAnswers();
        },

        onUpdate: function () {
            updateAnswers();
        }

    });

});


// =====================================================
// UPDATE HIDDEN INPUT
// =====================================================

function updateAnswers() {

    document.querySelectorAll('.drop-zone').forEach(zone => {

        const row = zone.dataset.row;
        const col = zone.dataset.col;

        const input = document.getElementById(
            `answer-${row}-${col}`
        );

        const image = zone.querySelector('img');

        if (image) {

            input.value = image.dataset.image;

            console.log(
                `Posisi ${row}-${col}:`,
                image.dataset.image
            );

        } else {

            input.value = '';

        }

    });

}

});
</script>

