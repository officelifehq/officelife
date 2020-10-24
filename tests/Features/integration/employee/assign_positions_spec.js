describe('Employee - Assign positions', function () {
  it('should assign a position and remove it as administrator', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createTeam('product');

    // create a position
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=position-admin-link]').click();
    cy.get('[data-cy=add-position-button]').click();
    cy.get('[data-cy=add-title-input]').type('CEO');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.visit('/1/employees/1');

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal-blank]').click();
    cy.get('[data-cy=list-position-1]').click();
    cy.get('[data-cy=open-position-modal]').contains('CEO');
    cy.hasAuditLog('Assigned the position called CEO to admin@admin.com', '/1/employees/1');
    cy.hasEmployeeLog('Assigned the position called CEO', '/1/employees/1');

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal').click();
    cy.get('[data-cy=position-reset-button]').click();
    cy.get('[data-cy=open-position-modal-blank]').should('not.contain', 'CEO');
    cy.hasAuditLog('Removed the position called CEO to admin@admin.com', '/1/employees/1');
    cy.hasEmployeeLog('Removed the position called CEO', '/1/employees/1');
  });

  it('should assign a position and remove it as hr', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createTeam('product');

    // create a position
    cy.get('[data-cy=header-adminland-link]').click();
    cy.get('[data-cy=position-admin-link]').click();
    cy.get('[data-cy=add-position-button]').click();
    cy.get('[data-cy=add-title-input]').type('CEO');
    cy.get('[data-cy=modal-add-cta]').click();

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200);
    });
    cy.visit('/1/employees/1');

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal-blank]').click();
    cy.get('[data-cy=list-position-1]').click();
    cy.get('[data-cy=open-position-modal]').contains('CEO');
    cy.hasEmployeeLog('Assigned the position called CEO', '/1/employees/1');

    // Open the modal to assign a team and select the first line
    cy.get('[data-cy=open-position-modal').click();
    cy.get('[data-cy=position-reset-button]').click();
    cy.get('[data-cy=open-position-modal-blank]').should('not.contain', 'CEO');
    cy.hasEmployeeLog('Removed the position called CEO', '/1/employees/1');
  });

  it('should not let a normal user assign positions', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.changePermission(1, 300);
    cy.visit('/1/employees/1');

    cy.contains('No position set');
    cy.get('[data-cy=open-position-modal-blank]').should('not.exist');
  });
});
