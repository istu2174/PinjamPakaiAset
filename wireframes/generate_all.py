"""
Master runner for generating all 33 B&W wireframes in SIPANDA.
"""

import os
import sys

# Ensure current directory is in sys.path
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from generate_batch1 import run_batch1
from generate_batch2 import run_batch2
from generate_batch3 import run_batch3

def main():
    print("==================================================")
    print("SIPANDA B&W WIREFRAME GENERATION - ALL 33 PAGES")
    print("==================================================")
    run_batch1()
    run_batch2()
    run_batch3()
    print("==================================================")
    print("SUCCESS: All 33 B&W SVG Wireframes generated successfully!")
    print("==================================================")

if __name__ == '__main__':
    main()
