<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;
use App\Services\MediaService;

class ComplaintController extends Controller
{
    public function index(): void
    {
        $this->render('public.complaints.index', [
            'title' => 'ศูนย์รับเรื่องร้องเรียนและข้อเสนอแนะ',
        ]);
    }

    public function submit(): void
    {
        $data = $this->validate([
            'category' => 'required',
            'subject' => 'required|min:5|max:255',
            'description' => 'required|min:10',
            'complainant_name' => 'required',
            'complainant_phone' => 'required',
        ]);

        // Generate Ticket Number (RC-COM-YYYY-XXXXX)
        $year = date('Y') + 543;
        $count = (int) Database::value("SELECT COUNT(*) FROM complaints WHERE YEAR(created_at) = YEAR(NOW())") + 1;
        $ticketNo = sprintf("RC-COM-%d-%05d", $year, $count);

        // Handle attachment if uploaded
        $attachmentPath = null;
        if ($this->request->hasFile('attachment')) {
            try {
                $uploadResult = MediaService::upload($this->request->file('attachment'), 'complaints');
                $attachmentPath = $uploadResult['path'];
            } catch (\Exception $e) {
                // proceed without failing entire submission or return error
            }
        }

        $sql = "INSERT INTO complaints (ticket_no, category, subject, description, complainant_name, complainant_phone, complainant_email, attachment, status, priority, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'received', 'normal', NOW())";

        $complaintId = Database::insert($sql, [
            $ticketNo,
            $data['category'],
            $data['subject'],
            $data['description'],
            $data['complainant_name'],
            $data['complainant_phone'],
            $this->request->input('complainant_email'),
            $attachmentPath
        ]);

        // Insert initial log
        Database::execute("INSERT INTO complaint_logs (complaint_id, action, note, created_at) VALUES (?, 'ส่งเรื่องร้องเรียนเข้าสู่ระบบ', 'ระบบได้รับเรื่องร้องเรียนเรียบร้อยแล้ว รอเจ้าหน้าที่ตรวจสอบ', NOW())", [$complaintId]);

        if ($this->request->isAjax()) {
            $this->json([
                'success' => true,
                'message' => 'ส่งเรื่องร้องเรียนสำเร็จ',
                'ticket_no' => $ticketNo,
            ]);
        } else {
            $this->redirect(url('complaints/track?ticket=' . urlencode($ticketNo)));
        }
    }

    public function track(): void
    {
        $ticket = $this->request->query('ticket');
        $complaint = null;
        $logs = [];

        if (!empty($ticket)) {
            $complaint = Database::first("SELECT * FROM complaints WHERE ticket_no = ? LIMIT 1", [trim($ticket)]);
            if ($complaint) {
                $logs = Database::query("SELECT * FROM complaint_logs WHERE complaint_id = ? ORDER BY created_at ASC", [$complaint['id']]);
            }
        }

        $this->render('public.complaints.track', [
            'title' => 'ติดตามสถานะเรื่องร้องเรียน',
            'ticket' => $ticket,
            'complaint' => $complaint,
            'logs' => $logs,
        ]);
    }
}
