$(document).ready(function() {

    let roomId = $(this).data("id");

    if (roomId) {
        $("#rooms-container").append(`
            <input type="hidden" name="deleted_rooms[]" value="${roomId}">
        `);
    }

    $(document).on("click", ".remove-room-btn", function () {
        let parent = $(this).closest(".accordion-item");
        parent.remove();
    });


    $(document).on("click", ".remove-room-btn", function () {
        let parent = $(this).closest(".accordion-item");
        let roomId = $(this).data("id");

        if (roomId) {
            // mark for delete on server
            $("#rooms-container").append(`
                <input type="hidden" name="deleted_rooms[]" value="${roomId}">
            `);
        }

        parent.remove();
    });


    let roomIndex = $(".accordion-item").length;     

    function loadRoomFields(index, bhkValue, storedRoomDetails = {}, storedCommonDetails = {}) {
        let wrapper = $(`#rooms_wrapper_${index}`);
        wrapper.html("");

        // Convert BHK to number of rooms
        let roomsCount = 0;
        switch (bhkValue) {
            case "1 RK":
                roomsCount = 1;
                break;
            case "1_bhk":
                roomsCount = 1;
                break;
            case "2_bhk":
                roomsCount = 2;
                break;
            case "3_bhk":
                roomsCount = 3;
                break;
            case "4_bhk":
                roomsCount = 4;
                break;
            case "5_bhk":
                roomsCount = 5;
                break;
            default:
                roomsCount = 0;
        }

        if (roomsCount === 0) return;

        let html = `<div class="room_bathroom">`;

        for (let i = 1; i <= roomsCount; i++) {
            html += `
                <div class="form-group fix-width-room">
                    <label class="light-label">Room ${i}</label>
                    <div class="input-group">
                        <input type="text" 
                            name="rooms[${index}][details][${i}][room]" 
                            class="form-control" 
                            value="${storedRoomDetails[i]?.room ?? ''}"
                            placeholder="Room">

                        <input type="text"  
                            name="rooms[${index}][details][${i}][bathroom]" 
                            class="form-control" 
                            value="${storedRoomDetails[i]?.bathroom ?? ''}"
                            placeholder="Bathroom">
                    </div>
                </div>
            `;
        }
        html += `</div>`;

        // Common details block
        html += `
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label class="light-label">Kitchen</label>
                        <input type="text" name="rooms[${index}][common][kitchen]" class="form-control" placeholder="Kitchen"
                                value="${storedCommonDetails.kitchen ?? ''}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">                        
                        <label class="light-label">Living</label>
                        <input type="text" name="rooms[${index}][common][living]" class="form-control" placeholder="Living"
                                value="${storedCommonDetails.living ?? ''}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="light-label">Balcony</label>
                        <input type="text" name="rooms[${index}][common][balcony]" class="form-control" placeholder="Balcony"
                            value="${storedCommonDetails.balcony ?? ''}">                    
                    </div>
                </div>
                
                <div class="form-group fix-width-room3">
                    <label class="light-label">Tower / Lift</label>
                    <div class="input-group">
                        <select name="rooms[${index}][common][tower]" class="form-select">
                            <option value="">Select Tower</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                            <option value="G">G</option>
                            <option value="H">H</option>
                            <option value="I">I</option>
                            <option value="J">J</option>
                            <option value="K">K</option>
                        </select>

                        <select name="rooms[${index}][common][lift]" class="form-select">
                            <option value="">Storey</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                        </select>
                    </div>
                </div>    

                <div class="form-group fix-width-room3">
                    <label class="light-label">Units on floor / Storey</label>
                    <div class="input-group">
                        <select name="rooms[${index}][common][units_on_floor]" class="form-select">
                            <option value="">Unit</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>

                        <select name="rooms[${index}][common][storey]" class="form-select">
                            <option value="">Lift</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div> 

                <div class="form-group fix-width-room3">
                    <label class="light-label">Carpet/Format</label>
                    <div class="input-group">
                        <input type="text" name="rooms[${index}][common][carpet]" class="form-control" placeholder="Carpet">                        
                        <select name="rooms[${index}][common][format]" class="form-select">
                            <option value="">Format</option><option value="sq.yd.">sq.yd.</option><option value="sq.ft.">sq.ft.</option>
                        </select>
                    </div>
                </div>
                </div>
            </div>
        `;

        wrapper.append(html);
    }

    // CHANGE EVENT
    $(document).on("change", ".bhk_selector", function () {

        let accordion = $(this).closest(".accordion-item");
        let index = accordion.data("index");
        let selectedBHK = $(this).val();

        let storedRoom = accordion.data("room_details") || {};
        let storedCommon = accordion.data("common_details") || {};

        loadRoomFields(index, selectedBHK, storedRoom, storedCommon);
    });

    // Trigger change on page load (for EDIT mode)
    setTimeout(function () {
        $(".bhk_selector").each(function () {
            if ($(this).val()) $(this).trigger("change");
        });
    }, 200);

    $(".add-room").click(function () {
        addNewRoomAccordion(roomIndex);
        roomIndex++;
    });    
    
    $(document).on('click', '.remove-room', function () {
        $(this).closest('.accordion-item').remove();
    });

    function addNewRoomAccordion(index) {        
        let html = `
            <div class="accordion-item mb-3" id="room-${index}" data-index="${index}">
                <h2 class="accordion-header">
                    <a href="javascript:0" class="accordion-button collapsed" data-bs-toggle="collapse" data-target="#collapse${index}">
                        Bhk Details
                    </a>
                </h2>

                <div id="collapse${index}" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-3 col-6">  
                                <div class="form-group roomError">                              
                                    <label class="light-label">Select Room<span class="req">*</span></label>
                                    <select name="rooms[${index}][room]" class="form-select bhk_selector">
                                        <option value="">BHK</option>   
                                        <option value="1 RK">1rk</option>
                                        <option value="1_bhk">1 Bhk</option>
                                        <option value="2_bhk">2 Bhk</option>
                                        <option value="3_bhk">3 Bhk</option>
                                        <option value="4_bhk">4 Bhk</option>
                                        <option value="5_bhk">5 Bhk</option>                                 
                                    </select>
                                    <small class="error full-width roomError"></small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group price1Error">
                                    <label class="light-label">
                                        <input type="hidden" name="rooms[${index}][price_on_request]" value="No">
                                        <input type="checkbox" class="price_on_request" name="rooms[${index}][price_on_request]" value="Yes">
                                        {{ strtolower(auth()->user()->role) === 'user' ? 'Rent Amount' : 'Price on Request' }}
                                        <span class="req">*</span>                                                        
                                    </label>

                                    <div class="price_wrapper">                            
                                        <input type="number" id="price" name="rooms[${index}][price]"
                                        class="form-control required-field" placeholder="Enter Price"
                                        value="{{ old('rooms.' . $index . '.price', $room->price ?? '') }}">
                                    </div>
                                    <small class="error full-width"></small> 
                                    <input type="hidden" name="rooms[${index}][id]" value="">
                                </div>
                            </div>
                            <div class="col-md-5 col-6">
                                <div class="form-group">
                                    <label class="light-label">Floor Map</label>
                                    <input type="file" name="rooms[${index}][image]" accept="image/*" class="form-control" >                             
                                </div>
                            </div>
                            <div class="col-md-1 col-6">
                                <button type="button" class="btn-delete remove-room mt-5">
                                    <span class="sprites"></span>
                                </button>
                            </div>
                        </div>
                        
                        <div id="rooms_wrapper_${index}" class="room-fields-wrapper"></div>
                    </div>
                </div>
            </div>`;        
        $("#rooms-container").append(html);
    }
});