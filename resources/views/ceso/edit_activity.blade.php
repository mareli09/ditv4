@extends('layouts.ceso')

@section('title', 'Edit Activity - CESO')

@section('content')

<div class="container-fluid">
    <h3 class="fw-bold mb-3">Edit Activity</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('ceso.activities.update', $activity->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Activity Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $activity->title) }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control" value="{{ old('venue', $activity->venue) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($activity->start_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($activity->end_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $activity->start_time) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $activity->end_time) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Conducted By</label>
                    <input type="text" name="conducted_by" class="form-control" value="{{ old('conducted_by', $activity->conducted_by) }}">
                </div>

                <h5 class="mt-4">Invited Participants</h5>
                
                <!-- Add Participant Form -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Participant Type</label>
                                <select id="participantType" class="form-select">
                                    <option value="">-- Select Role --</option>
                                    <option value="faculty">Faculty</option>
                                    <option value="staff">Staff (IT/CESO)</option>
                                    <option value="student">Student</option>
                                    <option value="community">Community Member</option>
                                    <option value="other">Other (Not in Database)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Person</label>
                                <select id="personSelect" class="form-select" disabled>
                                    <option value="">-- Choose from list --</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3" id="otherNameDiv" style="display: none;">
                            <div class="col-md-6">
                                <label class="form-label">Enter Name</label>
                                <input type="text" id="otherName" class="form-control" placeholder="Full name">
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-success" id="addParticipantBtn" onclick="addParticipant()">
                            <i class="fa fa-plus"></i> Add Participant
                        </button>
                    </div>
                </div>

                <!-- Invited Participants List -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <strong>Invited Participants (<span id="participantCount">0</span>)</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm" id="participantsTable" style="display: none;">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="participantsBody">
                            </tbody>
                        </table>
                        <p id="noParticipantsMsg" class="text-muted">No participants added yet.</p>
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <label class="form-label">Fee / Expenses (₱)</label>
                    <input type="number" name="fee" class="form-control" value="{{ old('fee', $activity->fee) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachments</label>
                    <input type="file" name="attachments[]" class="form-control" multiple>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $activity->description) }}</textarea>
                </div>

                <button class="btn btn-primary w-100">Update Activity</button>

                <!-- Hidden container for participant data -->
                <div id="participantsData" style="display: none;"></div>

            </form>

        </div>
    </div>
</div>

<script>
// Database users data passed from controller
const facultyData = @json($faculty);
const staffData = @json($staff);
const studentsData = @json($students);
const communityData = @json($community);

// Participants array to track added participants
let participantsArray = [];

// Seed existing participants from activity
const existingParticipants = @json($activity->participants->map(function($p){
    return [
        'id' => $p->user_id ? $p->user_id : ('other_' . uniqid()),
        'name' => $p->user?->name ?? $p->name,
        'type' => $p->participant_type
    ];
}));

if (existingParticipants && existingParticipants.length) {
    participantsArray = existingParticipants;
    // ensure hidden inputs are created
}

// Handle participant type change
document.getElementById('participantType').addEventListener('change', function() {
    const type = this.value;
    const personSelect = document.getElementById('personSelect');
    const otherNameDiv = document.getElementById('otherNameDiv');
    
    personSelect.innerHTML = '<option value="">-- Choose from list --</option>';
    personSelect.disabled = true;
    otherNameDiv.style.display = 'none';
    document.getElementById('otherName').value = '';
    
    if (type === 'faculty') {
        facultyData.forEach(person => {
            const option = document.createElement('option');
            option.value = person.id;
            option.textContent = person.name;
            option.dataset.name = person.name;
            personSelect.appendChild(option);
        });
        personSelect.disabled = false;
    } else if (type === 'staff') {
        staffData.forEach(person => {
            const option = document.createElement('option');
            option.value = person.id;
            option.textContent = person.name + ' (' + person.role + ')';
            option.dataset.name = person.name;
            personSelect.appendChild(option);
        });
        personSelect.disabled = false;
    } else if (type === 'student') {
        studentsData.forEach(person => {
            const option = document.createElement('option');
            option.value = person.id;
            option.textContent = person.name;
            option.dataset.name = person.name;
            personSelect.appendChild(option);
        });
        personSelect.disabled = false;
    } else if (type === 'community') {
        communityData.forEach(person => {
            const option = document.createElement('option');
            option.value = person.id;
            option.textContent = person.first_name + ' ' + person.last_name;
            option.dataset.name = person.first_name + ' ' + person.last_name;
            personSelect.appendChild(option);
        });
        personSelect.disabled = false;
    } else if (type === 'other') {
        otherNameDiv.style.display = 'block';
    }
});

// Add participant function
function addParticipant() {
    const type = document.getElementById('participantType').value;
    const personSelect = document.getElementById('personSelect');
    const otherName = document.getElementById('otherName').value;
    
    if (!type) {
        alert('Please select a participant type');
        return;
    }
    
    let participantId = null;
    let participantName = null;
    let participantType = null;
    
    if (type === 'other') {
        if (!otherName.trim()) {
            alert('Please enter a name');
            return;
        }
        participantName = otherName.trim();
        participantType = 'other';
        participantId = 'other_' + Math.random().toString(36).substr(2, 9);
    } else {
        if (!personSelect.value) {
            alert('Please select a person from the list');
            return;
        }
        participantId = personSelect.value;
        participantName = personSelect.options[personSelect.selectedIndex].dataset.name;
        participantType = type;
    }
    
    // Add to participants array
    participantsArray.push({
        id: participantId,
        name: participantName,
        type: participantType
    });
    
    // Update display
    updateParticipantsDisplay();
    
    // Reset form
    document.getElementById('participantType').value = '';
    document.getElementById('personSelect').innerHTML = '<option value="">-- Choose from list --</option>';
    document.getElementById('personSelect').disabled = true;
    document.getElementById('otherName').value = '';
    document.getElementById('otherNameDiv').style.display = 'none';
}

// Update participants display
function updateParticipantsDisplay() {
    const participantsBody = document.getElementById('participantsBody');
    const participantsTable = document.getElementById('participantsTable');
    const noParticipantsMsg = document.getElementById('noParticipantsMsg');
    const participantCount = document.getElementById('participantCount');
    
    participantCount.textContent = participantsArray.length;
    
    if (participantsArray.length === 0) {
        participantsTable.style.display = 'none';
        noParticipantsMsg.style.display = 'block';
    } else {
        participantsTable.style.display = 'table';
        noParticipantsMsg.style.display = 'none';
    }
    
    participantsBody.innerHTML = '';
    participantsArray.forEach((participant, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><span class="badge bg-info">${participant.type}</span></td>
            <td>${participant.name}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeParticipant(${index})">
                    <i class="fa fa-trash"></i> Remove
                </button>
            </td>
        `;
        participantsBody.appendChild(row);
    });
    
    // Update hidden inputs
    updateHiddenInputs();
}

// Remove participant
function removeParticipant(index) {
    participantsArray.splice(index, 1);
    updateParticipantsDisplay();
}

// Update hidden inputs for form submission
function updateHiddenInputs() {
    const container = document.getElementById('participantsData');
    container.innerHTML = '';
    
    participantsArray.forEach(participant => {
        if (participant.type === 'faculty') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invited_faculty_ids[]';
            input.value = participant.id;
            container.appendChild(input);
        } else if (participant.type === 'staff') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invited_staff_ids[]';
            input.value = participant.id;
            container.appendChild(input);
        } else if (participant.type === 'student') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invited_student_ids[]';
            input.value = participant.id;
            container.appendChild(input);
        } else if (participant.type === 'community') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invited_community_ids[]';
            input.value = participant.id;
            container.appendChild(input);
        } else if (participant.type === 'other') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invited_other_names[]';
            input.value = participant.name;
            container.appendChild(input);
        }
    });
}

// Allow Enter key to add participant
document.getElementById('personSelect').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') addParticipant();
});

document.getElementById('otherName').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') addParticipant();
});

// Initialize display with existing participants
updateParticipantsDisplay();
</script>

@endsection