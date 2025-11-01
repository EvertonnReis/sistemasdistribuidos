#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Script Python para gerar relatório de cursos
Conecta ao banco de dados MySQL e lista todos os cursos com o número de alunos inscritos
"""

import os
import sys
import json
from datetime import datetime
import mysql.connector
from mysql.connector import Error
from dotenv import load_dotenv
load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), '.env'))

def get_database_connection():
    """Cria conexão com o banco de dados MySQL usando variáveis de ambiente"""
    try:
        # Lê configurações do .env (você pode usar python-dotenv para isso)
        connection = mysql.connector.connect(
            host=os.getenv('DB_HOST', '127.0.0.1'),
            port=os.getenv('DB_PORT', '3306'),
            database=os.getenv('DB_DATABASE', 'online_courses'),
            user=os.getenv('DB_USERNAME', 'root'),
            password=os.getenv('DB_PASSWORD', '')
        )
        
        if connection.is_connected():
            print(f"✓ Connected to MySQL database: {connection.get_server_info()}")
            return connection
            
    except Error as e:
        print(f"✗ Error connecting to MySQL: {e}", file=sys.stderr)
        sys.exit(1)

def generate_report(connection):
    """Gera relatório de cursos com número de alunos"""
    try:
        cursor = connection.cursor(dictionary=True)
        
        # Query para buscar cursos com contagem de inscrições
        query = """
            SELECT 
                c.id,
                c.title,
                c.slug,
                c.price,
                c.duration_hours,
                c.is_published,
                cat.name as category_name,
                COUNT(DISTINCT e.id) as total_students,
                COUNT(DISTINCT l.id) as total_lessons
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN enrollments e ON c.id = e.course_id
            LEFT JOIN lessons l ON c.id = l.course_id
            WHERE c.deleted_at IS NULL
            GROUP BY c.id, c.title, c.slug, c.price, c.duration_hours, c.is_published, cat.name
            ORDER BY total_students DESC, c.title
        """
        
        cursor.execute(query)
        courses = cursor.fetchall()
        
        # Gera relatório
        report = {
            'generated_at': datetime.now().isoformat(),
            'total_courses': len(courses),
            'courses': courses
        }
        
        # Salva relatório em JSON
        report_path = os.path.join(os.path.dirname(__file__), '../storage/reports')
        os.makedirs(report_path, exist_ok=True)
        
        report_file = os.path.join(report_path, f'course_report_{datetime.now().strftime("%Y%m%d_%H%M%S")}.json')
        
        with open(report_file, 'w', encoding='utf-8') as f:
            json.dump(report, f, indent=2, ensure_ascii=False, default=str)
        
        # Imprime resumo no console
        print("\n" + "="*80)
        print(" COURSE ENROLLMENT REPORT ".center(80, "="))
        print("="*80)
        print(f"\nGenerated at: {report['generated_at']}")
        print(f"Total courses: {report['total_courses']}\n")
        print("-"*80)
        print(f"{'ID':<5} {'Title':<35} {'Category':<20} {'Students':<10} {'Lessons':<10}")
        print("-"*80)
        
        for course in courses:
            print(f"{course['id']:<5} {course['title'][:34]:<35} {(course['category_name'] or 'N/A')[:19]:<20} {course['total_students']:<10} {course['total_lessons']:<10}")
        
        print("-"*80)
        print(f"\n✓ Report saved to: {report_file}")
        print("="*80 + "\n")
        
        cursor.close()
        return report
        
    except Error as e:
        print(f"✗ Error generating report: {e}", file=sys.stderr)
        sys.exit(1)

def main():
    """Função principal"""
    print("\n🐍 Python Course Report Generator\n")
    
    connection = get_database_connection()
    
    try:
        report = generate_report(connection)
        print("✓ Report generation completed successfully!")
        return 0
    finally:
        if connection.is_connected():
            connection.close()
            print("✓ Database connection closed\n")

if __name__ == "__main__":
    sys.exit(main())
