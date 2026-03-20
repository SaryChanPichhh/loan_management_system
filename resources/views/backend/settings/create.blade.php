@extends('backend.layout.master')

@section('contents')
    <div class="page-wrapper mt-1 ">
        <form class="row g-3 m-4">
            <div class="col-md-6 mb-3">
                <label class="form-label">រូបិយប័ណ្ណមូលដ្ឋាន</label>
                <select class="form-select">
                    <option value="">-- ជ្រើសរើស --</option>
                    <option>USD</option>
                    <option>KHR</option>
                    <option>EUR</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">រូបិយប័ណ្ណគោលដៅ</label>
                <select class="form-select">
                    <option value="">-- ជ្រើសរើស --</option>
                    <option>KHR</option>
                    <option>USD</option>
                    <option>EUR</option>
                </select>
            </div>

            <div class="col-md-6  mb-3">
                <label class="form-label">អត្រាប្តូរប្រាក់</label>
                <input type="number" step="0.00000001" class="form-control" placeholder="4100.00000000">
            </div>

            <div class="col-md-6  mb-3">
                <label class="form-label">កាលបរិច្ឆេទ</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-6  mb-3">
                <label class="form-label">ប្រភព</label>
                <input type="text" class="form-control" placeholder="National Bank / API">
            </div>

            <div class="col-md-6  mb-3">
                <label class="form-label">ឯកសារ</label>
                <input type="file" class="form-control">
            </div>

            <div class="col-md-6  mb-3">
                <label class="form-label">ស្ថានភាព</label>
                <select class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-12 text-end  mb-6">
                <button type="submit" class="btn btn-success">រក្សាទុក</button>
                <button type="reset" class="btn btn-secondary">បោះបង់</button>
            </div>
        </form>
    </div>
@endsection
