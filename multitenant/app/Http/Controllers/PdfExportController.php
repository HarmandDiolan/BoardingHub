<?php

namespace App\Http\Controllers;

use FPDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Tenant\Room;
use App\Models\User;
use App\Models\Tenant\Complaint;
use App\Models\Tenant\RoomRental as Rental;
use Illuminate\Support\Facades\Log;

class PdfExportController extends Controller
{
    // Generic PDF class that can be reused for all reports
    private function createPdf()
    {
        // Use the tenant ID instead of a missing "name" field
        $tenant = tenancy()->tenant;
        $tenantId = $tenant->id ?? 'Tenant';

        // Create a new custom PDF class with header/footer
        return new class($tenantId) extends FPDF {
            protected string $tenantId;
            protected string $reportTitle;

            public function __construct($tenantId, $reportTitle = 'Report')
            {
                parent::__construct();
                $this->tenantId = $tenantId;
                $this->reportTitle = $reportTitle;
            }

            function Header()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 10, 'Tenant ID: ' . $this->tenantId, 0, 1, 'C');
                $this->SetFont('Arial', 'B', 16);
                $this->Cell(0, 10, $this->reportTitle, 0, 1, 'C');
                $this->Ln(5);
                $this->Line(10, $this->GetY(), 200, $this->GetY());
                $this->Ln(5);
            }

            function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
                $this->Ln(5);
                $this->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 0, 'C');
            }

            public function setReportTitle($title)
            {
                $this->reportTitle = $title;
            }
        };
    }

    // Report selection view
    public function reportIndex()
    {
        return view('tenant.admin.reports.index');
    }

    // Main function to select which report to generate
    public function export(Request $request, $report_type = 'general')
    {
        try {
            Log::info('Starting PDF Export', [
                'report_type' => $report_type,
                'request_params' => $request->all(),
                'tenant_id' => tenant()->id
            ]);

            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');

            // Verify we're in the tenant context
            if (!tenant()) {
                throw new \Exception('Not in tenant context');
            }

            // Verify database connection
            try {
                $testQuery = Room::on('tenant')->first();
                Log::info('Test query result', ['result' => $testQuery]);
            } catch (\Exception $e) {
                Log::error('Database connection test failed', [
                    'error' => $e->getMessage()
                ]);
            }

            switch ($report_type) {
                case 'rooms':
                    return $this->roomsReport($dateFrom, $dateTo);
                case 'tenants': 
                    return $this->tenantsReport($dateFrom, $dateTo);
                case 'rentals':
                    return $this->rentalsReport($dateFrom, $dateTo);
                case 'complaints':
                    return $this->complaintsReport($dateFrom, $dateTo);
                default:
                    return $this->generalReport();
            }
        } catch (\Exception $e) {
            Log::error('PDF Export Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'report_type' => $report_type
            ]);
            
            return response()->json([
                'error' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    // General report (original sample)
    private function generalReport()
    {
        $pdf = $this->createPdf();
        $pdf->setReportTitle('General System Report');

        $data = [
            'This is a sample line.',
            'Another line of content.',
            'One more example for your PDF.'
        ];

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        foreach ($data as $line) {
            $pdf->Cell(0, 10, $line, 0, 1);
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="tenant-general-report.pdf"');
    }

    // Rooms report 
    private function roomsReport($dateFrom = null, $dateTo = null)
    {
        try {
            $pdf = $this->createPdf();
            $pdf->setReportTitle('Rooms Status Report');
            $pdf->AddPage();

            // Query rooms with date filter if provided
            $query = Room::on('tenant');
            if ($dateFrom && $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
            $rooms = $query->get();

            Log::info('Rooms query result', [
                'count' => $rooms->count(),
                'first_room' => $rooms ? $rooms->first() : null,
                'connection' => $query->getConnection()->getName(),
                'tenant_id' => tenant()->id
            ]);

            if ($rooms->isEmpty()) {
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'No rooms found in the system.', 0, 1, 'C');
                return response($pdf->Output('S'), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="rooms-report.pdf"');
            }

            // Display date range if provided
            if ($dateFrom && $dateTo) {
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(0, 10, "Date Range: $dateFrom to $dateTo", 0, 1, 'C');
                $pdf->Ln(5);
            }

            // Table header
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Room Number', 1, 0, 'C');
            $pdf->Cell(55, 10, 'Description', 1, 0, 'C');
            $pdf->Cell(35, 10, 'Price', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Created', 1, 1, 'C');

            // Table data
            $pdf->SetFont('Arial', '', 9);
            $totalPrice = 0;
            $availableCount = 0;
            $occupiedCount = 0;

            foreach ($rooms as $room) {
                $pdf->Cell(15, 10, $room->id, 1, 0, 'C');
                $pdf->Cell(30, 10, $room->room_number, 1, 0, 'L');
                
                // Handle long descriptions
                $description = $room->description ?? 'No description';
                if (strlen($description) > 40) {
                    $description = substr($description, 0, 37) . '...';
                }
                $pdf->Cell(55, 10, $description, 1, 0, 'L');
                
                $pdf->Cell(35, 10, 'PHP ' . number_format($room->price, 2), 1, 0, 'R');
                $pdf->Cell(30, 10, ucfirst($room->status), 1, 0, 'C');
                $pdf->Cell(25, 10, date('Y-m-d', strtotime($room->created_at)), 1, 1, 'C');
                
                // Calculate totals for summary
                $totalPrice += $room->price;
                if ($room->status == 'available') {
                    $availableCount++;
                } elseif ($room->status == 'occupied') {
                    $occupiedCount++;
                }
            }

            // Summary section
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Summary', 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 8, 'Total Rooms: ' . count($rooms), 0, 1);
            $pdf->Cell(0, 8, 'Available Rooms: ' . $availableCount, 0, 1);
            $pdf->Cell(0, 8, 'Occupied Rooms: ' . $occupiedCount, 0, 1);
            $pdf->Cell(0, 8, 'Total Room Value: PHP ' . number_format($totalPrice, 2), 0, 1);

            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="rooms-report.pdf"');
        } catch (\Exception $e) {
            Log::error('Room Report Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tenant_id' => tenant()->id
            ]);
            throw $e;
        }
    }

    // Tenants report
    private function tenantsReport($dateFrom = null, $dateTo = null)
    {
        try {
            $pdf = $this->createPdf();
            $pdf->setReportTitle('Users Report');
            $pdf->AddPage();

            // Query all users except admins
            $query = User::on('tenant')->where('role', '!=', 'admin');
            if ($dateFrom && $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
            $users = $query->get();

            Log::info('Users query result', [
                'count' => $users->count(),
                'first_user' => $users->first(),
                'roles' => $users->pluck('role')->unique()->toArray()
            ]);

            if ($users->isEmpty()) {
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'No users found in the system.', 0, 1, 'C');
                return response($pdf->Output('S'), 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="users-report.pdf"');
            }

            // Display date range if provided
            if ($dateFrom && $dateTo) {
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(0, 10, "Date Range: $dateFrom to $dateTo", 0, 1, 'C');
                $pdf->Ln(5);
            }

            // Table header
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'C');
            $pdf->Cell(45, 10, 'Name', 1, 0, 'C');
            $pdf->Cell(60, 10, 'Email', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Role', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Phone', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Status', 1, 1, 'C');

            // Table data
            $pdf->SetFont('Arial', '', 9);
            $roleCount = [];

            foreach ($users as $user) {
                // Count users by role
                $role = $user->role ?? 'undefined';
                $roleCount[$role] = ($roleCount[$role] ?? 0) + 1;

                $pdf->Cell(15, 10, $user->id, 1, 0, 'C');
                $pdf->Cell(45, 10, $user->name ?? 'N/A', 1, 0, 'L');
                $pdf->Cell(60, 10, $user->email ?? 'N/A', 1, 0, 'L');
                $pdf->Cell(25, 10, ucfirst($role), 1, 0, 'C');
                $pdf->Cell(25, 10, $user->phone ?? 'N/A', 1, 0, 'C');
                $pdf->Cell(20, 10, $user->status ?? 'Active', 1, 1, 'C');
            }

            // Summary section
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Summary', 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 8, 'Total Users: ' . count($users), 0, 1);
            
            // Show count by role
            foreach ($roleCount as $role => $count) {
                $pdf->Cell(0, 8, ucfirst($role) . ' Users: ' . $count, 0, 1);
            }

            // Show new users in last 30 days
            $recentUsers = $users->where('created_at', '>=', now()->subDays(30))->count();
            $pdf->Cell(0, 8, 'New Users (Last 30 Days): ' . $recentUsers, 0, 1);

            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="users-report.pdf"');

        } catch (\Exception $e) {
            Log::error('Users Report Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tenant_id' => tenant()->id
            ]);
            throw $e;
        }
    }

    // Rentals report
    private function rentalsReport($dateFrom = null, $dateTo = null)
    {
        try {
            $pdf = $this->createPdf();
            $pdf->setReportTitle('Rental Payments Report');
            $pdf->AddPage();

            // Query rentals with relationships
            $query = Rental::on('tenant')->with(['room', 'user']);
            if ($dateFrom && $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
            $rentals = $query->get();

            Log::info('Rentals query result', [
                'count' => $rentals->count(),
                'first_rental' => $rentals->first()
            ]);

            // Display date range if provided
            if ($dateFrom && $dateTo) {
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(0, 10, "Date Range: $dateFrom to $dateTo", 0, 1, 'C');
                $pdf->Ln(5);
            }

            // Table header
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Room', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Tenant', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Amount', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Status', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Due Date', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Payment Date', 1, 1, 'C');

            // Table data
            $pdf->SetFont('Arial', '', 9);
            $totalAmount = 0;
            $paidAmount = 0;
            $pendingAmount = 0;

            foreach ($rentals as $rental) {
                $pdf->Cell(15, 10, $rental->id, 1, 0, 'C');
                $pdf->Cell(30, 10, $rental->room ? $rental->room->room_number : 'N/A', 1, 0, 'L');
                $pdf->Cell(40, 10, $rental->user ? $rental->user->name : 'N/A', 1, 0, 'L');
                $pdf->Cell(30, 10, 'PHP ' . number_format($rental->amount, 2), 1, 0, 'R');
                $pdf->Cell(25, 10, ucfirst($rental->status), 1, 0, 'C');
                $pdf->Cell(25, 10, date('Y-m-d', strtotime($rental->due_date)), 1, 0, 'C');
                $pdf->Cell(25, 10, $rental->payment_date ? 
                    date('Y-m-d', strtotime($rental->payment_date)) : '-', 1, 1, 'C');
                
                // Calculate totals
                $totalAmount += $rental->amount;
                if ($rental->status == 'paid') {
                    $paidAmount += $rental->amount;
                } else {
                    $pendingAmount += $rental->amount;
                }
            }

            // Summary
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Summary', 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 8, 'Total Rentals: ' . count($rentals), 0, 1);
            $pdf->Cell(0, 8, 'Total Amount: PHP ' . number_format($totalAmount, 2), 0, 1);
            $pdf->Cell(0, 8, 'Paid Amount: PHP ' . number_format($paidAmount, 2), 0, 1);
            $pdf->Cell(0, 8, 'Pending Amount: PHP ' . number_format($pendingAmount, 2), 0, 1);
            $pdf->Cell(0, 8, 'Collection Rate: ' . 
                ($totalAmount > 0 ? round(($paidAmount / $totalAmount) * 100, 2) : 0) . '%', 0, 1);

            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="rentals-report.pdf"');
        } catch (\Exception $e) {
            Log::error('Rental Report Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // Complaints report
    private function complaintsReport($dateFrom = null, $dateTo = null)
    {
        try {
            $pdf = $this->createPdf();
            $pdf->setReportTitle('Complaints Report');
            $pdf->AddPage();

            // Query complaints with user relationship
            $query = Complaint::on('tenant')->with('user');
            if ($dateFrom && $dateTo) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
            $complaints = $query->get();

            Log::info('Complaints query result', [
                'count' => $complaints->count(),
                'first_complaint' => $complaints->first()
            ]);

            // Display date range if provided
            if ($dateFrom && $dateTo) {
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(0, 10, "Date Range: $dateFrom to $dateTo", 0, 1, 'C');
                $pdf->Ln(5);
            }

            // Table header
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Tenant', 1, 0, 'C');
            $pdf->Cell(65, 10, 'Subject', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Status', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Created', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Updated', 1, 1, 'C');

            // Table data
            $pdf->SetFont('Arial', '', 9);
            $openCount = 0;
            $inProgressCount = 0;
            $resolvedCount = 0;

            foreach ($complaints as $complaint) {
                $pdf->Cell(15, 10, $complaint->id, 1, 0, 'C');
                $pdf->Cell(40, 10, $complaint->user ? $complaint->user->name : 'N/A', 1, 0, 'L');
                
                // Handle long subjects
                $subject = $complaint->subject;
                if (strlen($subject) > 45) {
                    $subject = substr($subject, 0, 42) . '...';
                }
                $pdf->Cell(65, 10, $subject, 1, 0, 'L');
                
                $pdf->Cell(25, 10, ucfirst($complaint->status), 1, 0, 'C');
                $pdf->Cell(25, 10, date('Y-m-d', strtotime($complaint->created_at)), 1, 0, 'C');
                $pdf->Cell(25, 10, date('Y-m-d', strtotime($complaint->updated_at)), 1, 1, 'C');
                
                // Count by status
                if ($complaint->status == 'open') {
                    $openCount++;
                } elseif ($complaint->status == 'in_progress') {
                    $inProgressCount++;
                } elseif ($complaint->status == 'resolved') {
                    $resolvedCount++;
                }
            }

            // Summary
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Summary', 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 8, 'Total Complaints: ' . count($complaints), 0, 1);
            $pdf->Cell(0, 8, 'Open: ' . $openCount, 0, 1);
            $pdf->Cell(0, 8, 'In Progress: ' . $inProgressCount, 0, 1);
            $pdf->Cell(0, 8, 'Resolved: ' . $resolvedCount, 0, 1);
            $pdf->Cell(0, 8, 'Resolution Rate: ' . 
                (count($complaints) > 0 ? round(($resolvedCount / count($complaints)) * 100, 2) : 0) . '%', 0, 1);

            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="complaints-report.pdf"');
        } catch (\Exception $e) {
            Log::error('Complaint Report Generation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
