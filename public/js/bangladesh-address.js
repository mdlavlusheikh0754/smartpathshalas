// Bangladesh Address Data - Divisions only (sample)
const bangladeshData = {
    "খুলনা": {
        "বাগেরহাট": {
            "বাগেরহাট সদর": ["কাড়াপাড়া", "বেমরতা", "গোটাপাড়া", "বিষ্ণুপুর", "বারুইপাড়া"],
            "কচুয়া": ["বাধাল", "গজালিয়া", "ধোপাখালী", "মঘিয়া", "কচুয়া"]
        },
        "খুলনা": {
            "খুলনা সদর": ["আইচগাতী", "শ্রীফলতলা", "নৈহাটি"],
            "ডুমুরিয়া": ["ডুমুরিয়া", "মাগুরাঘোনা", "ভান্ডারপাড়া"]
        }
    },
    "ঢাকা": {
        "ঢাকা": {
            "সাভার": ["শিমুলিয়া", "ধামসোনা", "পাথালিয়া"],
            "ধামরাই": ["আমতা", "কুশুরা", "গাংগুটিয়া"]
        },
        "গাজীপুর": {
            "গাজীপুর সদর": ["মির্জাপুর", "কাউলতিয়া", "ভাওয়াল গড়"],
            "কালিয়াকৈর": ["ফুলবাড়ীয়া", "চাপাইর", "বোয়ালী"]
        }
    },
    "চট্টগ্রাম": {
        "চট্টগ্রাম": {
            "চট্টগ্রাম সদর": ["বৈরাগ", "বারশত", "রায়পুর"],
            "পটিয়া": ["কোলাগাঁও", "হাবিলাসদ্বীপ", "কুসুমপুরা"]
        },
        "কক্সবাজার": {
            "কক্সবাজার সদর": ["ইসলামপুর", "পোকখালী", "ইসলামাবাদ"],
            "উখিয়া": ["জালিয়াপালং", "রত্নাপালং", "হলদিয়াপালং"]
        }
    },
    "রাজশাহী": {
        "রাজশাহী": {
            "রাজশাহী সদর": ["রায়ঘাটি", "ঘাসিগ্রাম", "মৌগাছি"],
            "পবা": ["দর্শনপাড়া", "হুজুরিপাড়া", "দামকুড়া"]
        },
        "নাটোর": {
            "নাটোর সদর": ["ছাতনী", "তেবাড়িয়া", "দিঘাপতিয়া"],
            "বাগাতিপাড়া": ["পাঁকা", "জামনগর", "বাগাতিপাড়া"]
        }
    },
    "খুলনা": {
        "যশোর": {
            "যশোর সদর": ["হৈবতপুর", "লেবুতলা", "ইছালী"],
            "অভয়নগর": ["পায়রা", "চলিশিয়া", "প্রেমবাগ"]
        },
        "সাতক্ষীরা": {
            "সাতক্ষীরা সদর": ["বাঁশদহা", "কুশখালী", "বৈকারী"],
            "কলারোয়া": ["জয়নগর", "জালালাবাদ", "কয়লা"]
        }
    },
    "বরিশাল": {
        "বরিশাল": {
            "বরিশাল সদর": ["রায়পাশা-কড়াপুর", "কাশিপুর", "চরবাড়িয়া"],
            "বাকেরগঞ্জ": ["চরামদ্দি", "চরাদি", "দাড়িয়াল"]
        },
        "পটুয়াখালী": {
            "পটুয়াখালী সদর": ["লাউকাঠী", "বদরপুর", "ইটবাড়িয়া"],
            "বাউফল": ["কাছিপাড়া", "কালিশুরী", "ধুলিয়া"]
        }
    },
    "সিলেট": {
        "সিলেট": {
            "সিলেট সদর": ["কান্দিগাঁও", "খাদিমনগর", "খাদিমপাড়া"],
            "বিয়ানীবাজার": ["আলীনগর", "কুড়ারবাজার", "চরখাই"]
        },
        "মৌলভীবাজার": {
            "মৌলভীবাজার সদর": ["খলিলপুর", "মনুমুখ", "কামালপুর"],
            "শ্রীমঙ্গল": ["মির্জাপুর", "ভূনবীর", "শ্রীমঙ্গল"]
        }
    },
    "রংপুর": {
        "রংপুর": {
            "রংপুর সদর": ["মমিনপুর", "হরিদেবপুর", "চন্দনপাট"],
            "বদরগঞ্জ": ["কুতুবপুর", "গোপালপুর", "গোপীনাথপুর"]
        },
        "দিনাজপুর": {
            "দিনাজপুর সদর": ["চেহেলগাজী", "সুন্দরবন", "ফাজিলপুর"],
            "বিরামপুর": ["মুকুন্দপুর", "কাটলা", "খানপুর"]
        }
    },
    "ময়মনসিংহ": {
        "ময়মনসিংহ": {
            "ময়মনসিংহ সদর": ["অষ্টধার", "বোররচর", "দাপুনিয়া"],
            "ত্রিশাল": ["ধানীখোলা", "বৈলর", "কাঁঠাল"]
        },
        "জামালপুর": {
            "জামালপুর সদর": ["কেন্দুয়া", "শরীফপুর", "লক্ষীরচর"],
            "মেলান্দহ": ["কুলিয়া", "দুরমুঠ", "মাহমুদপুর"]
        }
    }
};

// Initialize dropdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    loadDivisions();
});

function loadDivisions() {
    const divisions = Object.keys(bangladeshData);
    const presentDiv = document.getElementById('present_division');
    const permanentDiv = document.getElementById('permanent_division');
    
    divisions.forEach(division => {
        const option1 = new Option(division, division);
        const option2 = new Option(division, division);
        presentDiv.add(option1);
        permanentDiv.add(option2);
    });
}

function loadDistricts(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    // Reset dependent dropdowns
    districtSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
    
    const selectedDivision = divisionSelect.value;
    if (selectedDivision && bangladeshData[selectedDivision]) {
        const districts = Object.keys(bangladeshData[selectedDivision]);
        districts.forEach(district => {
            const option = new Option(district, district);
            districtSelect.add(option);
        });
    }
}

function loadUpazilas(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    // Reset dependent dropdowns
    upazilaSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
    
    const selectedDivision = divisionSelect.value;
    const selectedDistrict = districtSelect.value;
    
    if (selectedDivision && selectedDistrict && bangladeshData[selectedDivision][selectedDistrict]) {
        const upazilas = Object.keys(bangladeshData[selectedDivision][selectedDistrict]);
        upazilas.forEach(upazila => {
            const option = new Option(upazila, upazila);
            upazilaSelect.add(option);
        });
    }
}

function loadUnions(type) {
    const divisionSelect = document.getElementById(`${type}_division`);
    const districtSelect = document.getElementById(`${type}_district`);
    const upazilaSelect = document.getElementById(`${type}_upazila`);
    const unionSelect = document.getElementById(`${type}_union`);
    
    // Reset union dropdown
    unionSelect.innerHTML = '<option value="">নির্বাচন করুন</option>';
    
    const selectedDivision = divisionSelect.value;
    const selectedDistrict = districtSelect.value;
    const selectedUpazila = upazilaSelect.value;
    
    if (selectedDivision && selectedDistrict && selectedUpazila && 
        bangladeshData[selectedDivision][selectedDistrict][selectedUpazila]) {
        const unions = bangladeshData[selectedDivision][selectedDistrict][selectedUpazila];
        unions.forEach(union => {
            const option = new Option(union, union);
            unionSelect.add(option);
        });
    }
}

function copyPresentAddress() {
    const checkbox = document.getElementById('sameAsPresent');
    
    if (checkbox.checked) {
        // Copy division
        const presentDiv = document.getElementById('present_division').value;
        document.getElementById('permanent_division').value = presentDiv;
        loadDistricts('permanent');
        
        // Wait for districts to load, then copy district
        setTimeout(() => {
            const presentDist = document.getElementById('present_district').value;
            document.getElementById('permanent_district').value = presentDist;
            loadUpazilas('permanent');
            
            // Wait for upazilas to load, then copy upazila
            setTimeout(() => {
                const presentUpa = document.getElementById('present_upazila').value;
                document.getElementById('permanent_upazila').value = presentUpa;
                loadUnions('permanent');
                
                // Wait for unions to load, then copy union
                setTimeout(() => {
                    const presentUnion = document.getElementById('present_union').value;
                    document.getElementById('permanent_union').value = presentUnion;
                    
                    // Copy address details
                    const presentDetails = document.querySelector('input[name="present_address_details"]').value;
                    document.getElementById('permanent_address_details').value = presentDetails;
                }, 100);
            }, 100);
        }, 100);
    } else {
        // Clear permanent address fields
        document.getElementById('permanent_division').value = '';
        document.getElementById('permanent_district').innerHTML = '<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>';
        document.getElementById('permanent_upazila').innerHTML = '<option value="">প্রথমে জেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_union').innerHTML = '<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>';
        document.getElementById('permanent_address_details').value = '';
    }
}
