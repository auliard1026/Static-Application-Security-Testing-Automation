import os
import subprocess
from fpdf import FPDF

source_dir = '/xampp/htdocs/user_auth'  

output_file = 'scan_results_user_auth.json'

def run_sast_scan(directory, output_file):
    try:
        print(f"Running Semgrep scan on {directory}...")
        subprocess.run(['semgrep', '--config=auto', '--json', '--output', output_file, directory], check=True)
        print("Scan completed successfully.")
    except subprocess.CalledProcessError as e:
        print(f"Error running scan: {e}")

def parse_results(output_file):
    if not os.path.exists(output_file):
        print(f"Error: Output file {output_file} not found.")
        return None
    
    with open(output_file, 'r') as f:
        results = f.read()
    
    return results

def generate_pdf_report(results, output_pdf):
    pdf = FPDF()
    pdf.add_page()
    pdf.set_font("Arial", size=12)

    pdf.cell(200, 10, txt="SAST Scan Results for user_auth (PHP/HTML)", ln=True, align='C')
    pdf.ln(10)

    pdf.multi_cell(0, 10, results)

    pdf.output(output_pdf)
    print(f"PDF report generated: {output_pdf}")

run_sast_scan(source_dir, output_file)

scan_results = parse_results(output_file)
if scan_results:
    generate_pdf_report(scan_results, 'user_auth_sast_report.pdf')
