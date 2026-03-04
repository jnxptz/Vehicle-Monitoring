# 🚘 Board Members Vehicle Monitoring System

## Executive Summary
The **Board Members Vehicle Monitoring System (BMVMS)** is an enterprise-grade information system designed to monitor, manage, and analyze the utilization of official vehicles assigned to board members and executive officials. The system centralizes vehicle data, trip activity, and administrative oversight to ensure accountability, operational efficiency, and data-driven decision-making.

This solution is suitable for **government agencies, academic institutions, and corporate organizations** that manage a fleet of official service vehicles.

---

## Problem Statement
Organizations that manage executive vehicles often rely on manual logbooks or fragmented monitoring methods. These approaches commonly result in:
- Limited visibility of vehicle usage  
- Inefficient tracking of assignments and trips  
- Weak accountability mechanisms  
- Delayed and inaccurate reporting  

The **BMVMS** addresses these challenges by providing a **secure, centralized, and auditable vehicle monitoring platform**.

---

## System Objectives
- Centralize monitoring of board members’ vehicles  
- Track vehicle assignments, trips, and utilization  
- Improve accountability in the use of official vehicles  
- Support administrative decision-making through accurate reports  
- Ensure system security through role-based access control  

---

## Core Functionalities

### Administrative Management
- Manage board member and administrator accounts  
- Register and assign official vehicles  
- Manage drivers and vehicle assignments  
- Control vehicle status (Active, Inactive, Under Maintenance)

### Vehicle Monitoring and Tracking
- Real-time or simulated vehicle location tracking  
- Trip history and route logging  
- Timestamped vehicle activity records  
- Vehicle utilization monitoring

### Maintenance and Compliance
- Maintenance scheduling and logging  
- Inspection and service records  
- Downtime and availability monitoring  

### Reporting and Analytics
- Vehicle usage and utilization reports  
- Trip summaries and activity logs  
- Maintenance and compliance reports  
- Exportable reports for auditing and review  

### Security and Access Control
- Role-based access (Administrator / Authorized User)  
- Secure authentication and session handling  
- Input validation and audit logging  

---

## System Architecture
The system follows a **layered architecture** to ensure scalability, maintainability, and security.

User Interface
     ↓
Application Logic
     ↓
Database Server
     ↓
Vehicle Monitoring / Tracking Module


---

## 📘 User Manual

### 1. Register User
Create a new user account using the registration form.

---

### 2. User Dashboard (After Registration)
After registration, the user dashboard displays:

> **"No vehicle assigned to your account."**

This indicates that the administrator has not yet assigned a vehicle.

---

### 3. Admin Dashboard
The Admin Dashboard allows administrators to:

- Export **Monthly** or **Yearly PDF Reports**
- Filter offices
- Monitor monthly budget expenses

Users must first be assigned to an office.

---

### 4. Assign User to Office
1. Open **Manage Users**
2. Select a user
3. Assign the appropriate office
4. Save changes

---

### 5. Register a Vehicle
1. Go to **Vehicles Tab**
2. Click **Register Vehicle** (upper-right corner)
3. Enter:
   - Assigned User
   - Vehicle Name
   - Plate Number
   - Driver
4. Submit the form

---

### 6. Updated User Dashboard
After vehicle assignment, the dashboard updates automatically.

If no fuel records exist, the message appears:

> **"No fuel recorded for the month."**

---

### 7. Fuel Slips and Maintenance Tabs
These tabs are initially empty. Records appear after entries are created by the administrator.

---

### 8. Add Fuel Slip
1. Open **Fuel Slips** tab
2. Click **Add Fuel Slip**
3. Fill out required information
4. Save the record

---

### 9. Dashboard Updates
After adding fuel or maintenance records, both **Admin** and **User dashboards** update automatically.

---

### 10. Maintenance Records
Maintenance follows the same workflow as fuel slips:

1. Open **Maintenance Tab**
2. Add record
3. Complete the form
4. Save

---

## 👥 Contributors
- **Project Master:** Janial M. Bacani  
- **Assistant:** Clarisahaina R. Gonting  

---

## 📄 License
This project is intended for **academic and institutional use**.

You may modify and reuse this project with proper credit to the authors.

## 👥 Contributors

Project Master: Janial M. Bacani

Assistant: Clarisahaina R. Gonting

## 📄 License

This project is for academic and institutional use.
You may modify and reuse it with proper credit.
