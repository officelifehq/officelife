describe('Help - Show and validate help for all features', function () {
  it('should check that all links about help are valid', function () {
    cy.login();

    cy.createCompany((companyId) => {

      cy.visit('/'+companyId+'/dashboard');

      // make sure the toggle feature exists
      cy.get('[data-cy=layout-show-help]').click();
      cy.wait(200);
      cy.get('[data-cy=layout-show-help]').should('not.exist');
      cy.get('[data-cy=layout-hide-help]').click();
      cy.wait(200);
      cy.get('[data-cy=layout-hide-help]').should('not.exist');
      cy.get('[data-cy=layout-show-help]').click();


      /*–----------------------
      * DASHBOARD
      */
      // work from home
      cy.get('[data-cy=help-icon-work-from-home]').should('exist');
      cy.get('[data-cy=help-icon-work-from-home]')
        .invoke('attr', 'data-url').should('include', 'employee-management.html#work-from-home');


      /*–----------------------
      * ADMINLAND
      */
      // hardware
      cy.get('[data-cy=header-adminland-link]').click();
      cy.get('[data-cy=hardware-admin-link]').click();
      cy.get('[data-cy=add-hardware-button]').click();
      cy.get('[data-cy=help-icon-hardware]')
        .invoke('attr', 'data-url').should('include', 'introduction.html');

      // locking
      cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false, (employeeId) => {
        cy.get('[data-cy=lock-account-'+employeeId+']').click();
        cy.get('[data-cy=help-icon-employee-lock]')
          .invoke('attr', 'data-url').should('include', 'employee-management.html#locking-an-employee');

        // deleting employee
        cy.get('[data-cy=header-adminland-link]').click();
        //cy.get('[data-cy=employee-admin-link]').click()

        cy.visit('/'+companyId+'/account/employees/'+employeeId+'/delete');
        cy.get('[data-cy=help-icon-employee-delete]')
          .invoke('attr', 'data-url').should('include', 'employee-management.html#deleting-an-employee');
      });
    });
  });
});
